<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TemporarySendMandrillNewsletter extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'newsletter:temporarysendmandrillnewsletter';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Temporary Send Mandrill Newsletter';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$id = 3; // set newsletter id here
		// begin sending email
		$this->send($id);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		/*
		return array(
			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
		*/
		return array(
			
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		/*
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
		*/
		return array(
			
		);
	}

	// function to send newsletter
	public function send( $newsletter_id )
	{
		$list = Newsletters::findOrFail($newsletter_id);

		// then, the data
		$from_name = $list->sender_name;
		$from_email = $list->sender_email;
		$subject = $list->subject;
		$content = $list->content;
		$category_ids = $list->category_ids;
		$source_ids = $list->source_ids;
		$location_ids = $list->location_ids;
		$attachment_collections = $list->attachments;
		$sending_time = $list->sending_time;

		// get members' emails
		// belong to target category and subscribe
		$members = Members::where('is_subscribe', 1)
		->where('is_email_verified', 0)->get();

		$arr_mandrill_members = array();
		$arr_mandrill = array();

		foreach( $members as $member )
		{				
			$arr_mandrill_members[] = array(					
				"email" => $member->email,
				"name" => $member->first_name . " " . $member->last_name,
				"type" => "to"
			);

			$arr_mandrill[] = array(
				'id' => $member->id,
				'email' => $member->email,
				'first_name' => $member->first_name,
				'last_name' => $member->last_name,
				'hashed_email' => Hash::make($member->email)
			);
		}

		/* HANDLING ATTACHMENTS */
		$attachments = array();
		$arr_attachments = array(); // attachment for Mandrill

		if ( strlen( $attachment_collections ) > 0 )
		{
			// remove the first and last semicolon
			$attachment_collections = substr( $attachment_collections, 0, strlen( $attachment_collections ) - 1 );
			$attachment_collections = substr( $attachment_collections, 1, strlen( $attachment_collections ) );

			$attachment_collections = explode(";", $attachment_collections);

			$attachments = Medias::whereIn('url', $attachment_collections)->get();

			foreach( $attachments as $attachment )
			{
				// attachment for Mandrill
				$arr_attachments[] = array(					
					"type" => $attachment->type,
					"name" => $attachment->name,
					"content" => base64_encode( file_get_contents( rawurldecode($attachment->url) ) )
				);
			}
		}
		/* END OF HANDLING ATTACHMENTS */

		// fix the format of content and subject
		$mandrill_content = stripslashes( rawurldecode($content) );
		$subject = stripslashes( $subject );
		$from_name = stripslashes( $from_name );

		/* MANDRILL */
		/* -------------------------------------------- */
		// handle the newsletters to be sent by Mandrill
		if ( count( $arr_mandrill_members ) > 0 )
		{
			// HTML tag to update member's detail
			$update_member_detail_link_mandrill = '*|UPDATEMEMBERLINK|*';

			// HTML tag to unsubscribe
			$unsubscribe_link_mandrill = '*|UNSUBSCRIBELINK|*';

			// HTML tag to email link
			$email_link_mandrill = '*|EMAILLINK|*';

			// HTML tag to first name link
			$firstname_link_mandrill = '*|FIRSTNAMELINK|*';

			// HTML tag to last name link
			$lastname_link_mandrill = '*|LASTNAMELINK|*';

			// append the update member's tag to current content
			// $mandrill_content .= $update_member_detail_link_mandrill . $unsubscribe_link_mandrill;

			$mandrill_content = str_replace( '[[email]]', $email_link_mandrill, $mandrill_content );
			$mandrill_content = str_replace( '[[unsubscribe]]', $unsubscribe_link_mandrill, $mandrill_content );
			$mandrill_content = str_replace( '[[update]]', $update_member_detail_link_mandrill, $mandrill_content );
			$mandrill_content = str_replace( '[[firstname]]', $firstname_link_mandrill, $mandrill_content );
			$mandrill_content = str_replace( '[[lastname]]', $lastname_link_mandrill, $mandrill_content );

			// variable array for merge vars to update content
			$arr_merge_vars = array();

			foreach( $arr_mandrill as $member )
			{
				echo $member['email'];
				echo '<br /><br />';

				$arr_merge_vars[] = array(
          'rcpt' => ($member['email']),
          'vars' => array(
            array(
            	'name' => 'updatememberlink',
            	'content' => 'http://admin.kejorahq.com/confirmation?m='.$member['hashed_email'].'&i='.($member['id'])
            ),
            array(
            	'name' => 'unsubscribelink',
            	'content' => 'http://admin.kejorahq.com/confirmation/unsubscribe?m='.$member['hashed_email'].'&i='.($member['id'])
            ),
            array(
            	'name' => 'emaillink',
            	'content' => $member['email']
            ),
            array(
            	'name' => 'firstnamelink',
            	'content' => $member['first_name']
            ),
            array(
            	'name' => 'lastnamelink',
            	'content' => $member['last_name']
            )
          )
        );
			}

			$mandrill_key = Config::get('thirdparty_mail_settings.mandrill_key');

			$mandrill = new Mandrill($mandrill_key);
	    $message = array(
        'html' => $mandrill_content,
        'text' => '',
        'subject' => $subject,
        'from_email' => $from_email,
        'from_name' => $from_name,
        'to' => $arr_mandrill_members,
        'headers' => array('Reply-To' => $from_email),
        'important' => false,
        'track_opens' => null,
        'track_clicks' => null,
        'auto_text' => null,
        'auto_html' => null,
        'inline_css' => null,
        'url_strip_qs' => null,
        'preserve_recipients' => null,
        'view_content_link' => null,
        'bcc_address' => '',
        'tracking_domain' => null,
        'signing_domain' => null,
        'return_path_domain' => null,
        'merge' => true,
        'global_merge_vars' => array(
          /*
            array(
                'name' => 'merge1',
                'content' => 'merge1 content'
            )
          */
        ),
        'merge_vars' => $arr_merge_vars
          /*
            array(
                'rcpt' => 'recipient.email@example.com',
                'vars' => array(
                    array(
                        'name' => 'merge2',
                        'content' => 'merge2 content'
                    )
                )
            )
          */
        ,
        'tags' => array(/*'password-resets'*/),
        'google_analytics_domains' => array('kejorahq.com'),
        'google_analytics_campaign' => 'hello@kejorahq.com',
        'metadata' => array('website' => 'www.kejorahq.com'),
        'recipient_metadata' => array(
          /*
            array(
                'rcpt' => 'recipient.email@example.com',
                'values' => array('user_id' => 123456)
            )
          */
        ),
        'attachments' => $arr_attachments,
        'images' => array(
            
        )
	    );
	    $async = false;
	    $ip_pool = 'Main Pool';
	    $send_at = '';
	    $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);

	    // for each results, store it into newsletter_log
	    foreach( $result as $detail )
	    {
	    	$current_date = date("Y-m-d H:i:s");

	    	$newsletter_log = new NewsletterLog();

	    	$newsletter_log->email = $detail['email'];
	    	$newsletter_log->newsletter_id = $newsletter_id;
	    	$newsletter_log->source = "mandrill";
	    	$newsletter_log->message_id = $detail['_id'];
	    	$newsletter_log->sent_date = $current_date;
	    	$newsletter_log->sent_status = $detail['status'];
	    	$newsletter_log->open_date = "0000-00-00 00:00:00";
	    	$newsletter_log->open_status = "";
	    	$newsletter_log->created_at = $current_date;
	    	$newsletter_log->updated_at = $current_date;

	    	$newsletter_log->save();

	    	// if the status is rejected
	    	if ( $detail['status'] == "rejected" )
	    	{
	    		$rejected_member = Members::where('email', $detail['email'])->update(array('is_email_verified' => 2));
	    	}
	    }
		}

		$list->already_sent = 1; // set the mail to already sent
		// update the status to Online because it sends already and it is sent
		$list->status = 1;
		$list->update();
	}

}
