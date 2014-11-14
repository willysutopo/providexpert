<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TemporarySendAWSNewsletter extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'newsletter:temporarysendawsnewsletter';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Temporary Send AWS Newsletter';

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
		->where('is_email_verified', 1)
		->where('id', '>', 3606)->get();

		/* HANDLING ATTACHMENTS */
		$attachments = array();			

		if ( strlen( $attachment_collections ) > 0 )
		{
			// remove the first and last semicolon
			$attachment_collections = substr( $attachment_collections, 0, strlen( $attachment_collections ) - 1 );
			$attachment_collections = substr( $attachment_collections, 1, strlen( $attachment_collections ) );

			$attachment_collections = explode(";", $attachment_collections);

			$attachments = Medias::whereIn('url', $attachment_collections)->get();				
		}
		/* END OF HANDLING ATTACHMENTS */

		foreach( $members as $member )
		{
			$hashed_email = Hash::make($member->email);

			$ses_content = $content;

	    $to_email = $member->email;
	    $to_name = $member->first_name . " " . $member->last_name;

	    // decode the content first
	    $ses_content = stripslashes( rawurldecode( $ses_content ) );

	    // replace tag [[email]] with actual destination email
	    $ses_content = str_replace( '[[email]]', $to_email, $ses_content );
	    // replace tag [[unsubscribe]] with unsubscribe link
	    $ses_content = str_replace( '[[unsubscribe]]', 'http://admin.kejorahq.com/confirmation/unsubscribe?m='.$hashed_email.'&i='.($member->id), $ses_content );
	    // replace tag [[update]] with update member link
	    $ses_content = str_replace( '[[update]]', 'http://admin.kejorahq.com/confirmation?m='.$hashed_email.'&i='.($member->id), $ses_content );
	    // replace tag [[firstname]] with first name
	    $ses_content = str_replace( '[[firstname]]', $member->first_name, $ses_content );
	    // replace tag [[lastname]] with last name
	    $ses_content = str_replace( '[[lastname]]', $member->last_name, $ses_content );

	    // encode back the content
	    $ses_content = ( rawurlencode( addslashes( $ses_content ) ) );

	    $data_mail = array('content' => $ses_content);

	    $arr_attachment_full_link = array();
	    if ( !empty( $attachments ) )
			{
				foreach( $attachments as $attachment )
				{
					$arr_attachment_full_link[] = rawurldecode( $attachment->url );
				}
			}
	    
			Mail::send('emails.test', $data_mail, function($message) use ($to_name, $to_email, $from_name, $from_email, $subject, $arr_attachment_full_link )
			{					
				for ( $i = 0 ; $i < count( $arr_attachment_full_link ) ; $i++ )
				{
					$message->attach( $arr_attachment_full_link[$i] );
				}
				$message->sender($from_email, $from_name);
				$message->from($from_email, $from_name);
				$message->returnPath($from_email);
				$message->replyTo($from_email, $from_name);
				$message->to($to_email, $to_name);
				$message->subject($subject);
			});

			// save into log
			$current_date = date("Y-m-d H:i:s");

    	$newsletter_log = new NewsletterLog();

    	$newsletter_log->email = $to_email;
    	$newsletter_log->newsletter_id = $newsletter_id;
    	$newsletter_log->source = "amazon";
    	$newsletter_log->message_id = $newsletter_id;
    	$newsletter_log->sent_date = $current_date;
    	$newsletter_log->sent_status = 'sent';
    	$newsletter_log->open_date = "0000-00-00 00:00:00";
    	$newsletter_log->open_status = "";
    	$newsletter_log->created_at = $current_date;
    	$newsletter_log->updated_at = $current_date;

    	$newsletter_log->save();
		}

		$list->already_sent = 1; // set the mail to already sent
		// update the status to Online because it sends already and it is sent
		$list->status = 1;
		$list->update();
	}

}
