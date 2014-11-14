<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Mailgun\Mailgun;

class SendScheduledNewsletter extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'newsletter:sendscheduled';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Check Scheduled Newsletter';

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
		// get all newsletters which are not sent yet and the sending time is
		// scheduled to be sent later and not saved as draft
		$newsletters = Newsletters::where('already_sent', 0)
			->where('status', 1)
			->where('sending_time', '<>', '1970-01-01 00:00:00')
			->orderBy('id', 'asc')->get();

		// get current date and time
		$current_datetime = date("Y-m-d H:i:s");

		// get the newsletters that are going to be processed
		foreach( $newsletters as $newsletter )
		{
			$id = $newsletter->id;
			$subject = $newsletter->subject;
			$sending_time = $newsletter->sending_time;

			if ( $sending_time <= $current_datetime )
			{
				// format of data : subject and sending time
				$content = $subject . " : " . $sending_time . " AT " . $current_datetime . "\r\n";
				$file_path = storage_path().'/logs/newsletter.log';

				File::append($file_path, $content);

				// begin sending email
				$this->send($id);
			}
		}
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

	// get number of Mandrill's transactions within current month
	public function get_no_mandrill_transactions( $mandrill_key, $limit_mandrill )
	{
		$mandrill = new Mandrill($mandrill_key);

		$query = '*';
		$date_from = date("Y-m-01");
		$date_to = date("Y-m-t");
		$tags = array(

		);
		$senders = array();
		$api_keys = array($mandrill_key);
		$limit = $limit_mandrill;
		$result = $mandrill->messages->search($query, $date_from, $date_to, $tags, $senders, $api_keys, $limit);

		return count( $result );
	}

	// get number of Mailgun's transactions within current month
	public function get_no_mailgun_transactions( $mailgun_key, $limit_mailgun )
	{
		$mgClient = new Mailgun($mailgun_key);
		$domain = "kejorahq.com";

		$result = $mgClient->get("$domain/stats", array(
			'event' => array('sent'),
			'start-date' => date("Y-m-01")
		));

		$items = $result->http_response_body->items;
		$total_count = 0;

		foreach( $items as $item )
		{
			$total_count += $item->total_count;
		}

		// coding here
		return $total_count;
	}

	// function to send newsletter
	public function send( $id )
	{
		// set limit of Mandrill and Mailgun
		$limit_mandrill = 12000; // limit of Mandrill's free send per month
		$limit_mailgun = 10000; // limit of Mailgun's free send per month
		
		// get the correspoinding list by ID
		$list = Newsletters::findOrFail($id);

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

		$arr_category_ids = array();

		if ( trim( $category_ids ) != "" )
		{
			$category_ids = substr( $category_ids, 0, strlen( $category_ids ) - 1 );
			$category_ids = substr( $category_ids, 1, strlen( $category_ids ) );

			$arr_category_ids = explode(";", $category_ids);
		}

		// remove the first and last semicolon
		$arr_source_ids = array();

		if ( trim( $source_ids ) != "" )
		{
			$source_ids = substr( $source_ids, 0, strlen( $source_ids ) - 1 );
			$source_ids = substr( $source_ids, 1, strlen( $source_ids ) );

			$arr_source_ids = explode(";", $source_ids);
		}

		// remove the first and last semicolon
		$arr_location_ids = array();

		if ( trim( $location_ids ) != "" )
		{
			$location_ids = substr( $location_ids, 0, strlen( $location_ids ) - 1 );
			$location_ids = substr( $location_ids, 1, strlen( $location_ids ) );

			$arr_location_ids = explode(";", $location_ids);
		}

		// get members' emails
		// belong to target category and subscribe
		$members = Members::where('is_subscribe', 1)
		->where('is_email_verified', '<>', 2)
		->where(function($query) use($arr_category_ids)
			{
				// if the array of category is not empty
				if ( !empty($arr_category_ids) )
				{
					$query->where(function($query) use($arr_category_ids)
					{
						$query->where('category_ids', '<>', 0);
						$query->where(function($query) use($arr_category_ids)
						{
							// loop through the source array
							for ( $i = 0 ; $i < count( $arr_category_ids ) ; $i++ )
							{
								$query->orWhere( 'category_ids', 'like', '%;'.$arr_category_ids[$i].';%' );
							}
						});
					});
					
					//$query->where('category_ids', 0);
					
					$query->orWhere(function($query)
					{
						$query->where('category_ids', 0);
					});
				}
			})
		->where(function($query) use($arr_location_ids)
			{
				// if the array of location is not empty
				if ( !empty($arr_location_ids) )
				{
					$query->where(function($query) use($arr_location_ids)
					{
						$query->where('location_id', '<>', 0);
						$query->where('location_id', '<>', 12);					
						$query->whereIn('location_id', $arr_location_ids);
					});
				
					$query->orWhere(function($query)
					{
						$query->orWhere('location_id', 0);
						$query->orWhere('location_id', 12);
					});	
				}
			})
		->where(function($query) use($arr_source_ids)
			{
				// if the array of source is not empty
				if ( !empty($arr_source_ids) )
				{
					// loop through the source array
					for ( $i = 0 ; $i < count( $arr_source_ids ) ; $i++ )
					{
						$query->orWhere( 'source_ids', 'like', '%;'.$arr_source_ids[$i].';%' );
					}
				}
			})->get();

		// get keys for third party mail providers
		$mandrill_key = Config::get('thirdparty_mail_settings.mandrill_key');
		$mailgun_key = Config::get('thirdparty_mail_settings.mailgun_key');
		$ses_key = Config::get('thirdparty_mail_settings.ses_key');

		// divide which members to be put into mandrill, mailgun, or Amazon SES
		$arr_mandrill_members = array();
		$arr_mandrill = array();
		$mandrill_member_collections = "";

		$arr_mailgun_members = array();
		$arr_mailgun_email_tos = array();
		$arr_mailgun_unsubscribes = array();
		$arr_mailgun_updates = array();
		$arr_mailgun_first_names = array();
		$arr_mailgun_last_names = array();
		$mailgun_email_tos = "";
		$mailgun_member_collections = "";

		$arr_ses_members = array();
		$ses_member_collections = "";

		// get number of transactions of each Mandrill and Mailgun within current month
		$no_mandrill_transactions = $this->get_no_mandrill_transactions( $mandrill_key, $limit_mandrill );
		$no_mailgun_transactions = $this->get_no_mailgun_transactions( $mailgun_key, $limit_mailgun );

		// counter for members array
		$counter_mandrill = $no_mandrill_transactions;
		$counter_mailgun = $no_mailgun_transactions;
		$mailgun_i = 0;
		$mailgun_j = 0;

		// total number of each members ( Mandrill, Mailgun and SES )
		$total_mandrill = 0;
		$total_mailgun = 0;
		$total_ses = 0;

		foreach( $members as $member )
		{
			// if email is not verified yet, put into Mandrill
			if ( $member->is_email_verified == 0 )
			{
				// if the counter is still less than Mandrill's send limit
				if ( $counter_mandrill < $limit_mandrill )
				{
					$mandrill_member_collections .= $member->id . ",";

					$counter_mandrill++;
					$total_mandrill++;
				}
				else
				// if the counter is still less than Mailgun's send limit
				if ( $counter_mailgun < $limit_mailgun )
				{
					$mailgun_member_collections .= $member->id . ",";

					$counter_mailgun++;
					$total_mailgun++;
				}
				else
				// force to use Amazon SES if both Mandrill and Mailgun have exceeded the send x`limit
				{
					$ses_member_collections .= $member->id . ",";

					$total_ses++;
				}
			}
			else
			// if email is verified already, put into SES
			if ( $member->is_email_verified == 1 )
			{
				$ses_member_collections .= $member->id . ",";

				$total_ses++;
			}
			// if email is rejected ( status is 2, don't put anywhere, don't process the emails )
		}

		/* MANDRILL */
		/* -------------------------------------------- */
		// handle the newsletters to be sent by Mandrill
		if ( $total_mandrill > 0 )
		{
			$mandrill_member_collections = substr( $mandrill_member_collections, 0, strlen($mandrill_member_collections) - 1 );

			// the send mail by Mandrill is handled by its own handler in SendMandrillEmail Controller class
			// reason : the queue cannot accept BIG DATA so we handle the big data in its own controller
			Queue::push('SendMandrillEmail', array("newsletter_id" => $id, "member_collections" => $mandrill_member_collections), 'mandrillemail');			
		}

		/* MAILGUN */
		/* -------------------------------------------- */
		// handle the newsletters to be sent by Mailgun
		if ( $total_mailgun > 0 )
		{
			$mailgun_member_collections = substr( $mailgun_member_collections, 0, strlen($mailgun_member_collections) - 1 );

			// the send mail by Mailgun is handled by its own handler in SendMailgunEmail Controller class
			// reason : the queue cannot accept BIG DATA so we handle the big data in its own controller
			Queue::push('SendMailgunEmail', array("newsletter_id" => $id, "member_collections" => $mailgun_member_collections), 'mailgunemail');
		}

		/* AMAZON SES */
		/* -------------------------------------------- */
		// handle the newsletters to be sent by Amazon SES
		if ( $total_ses > 0 )
		{
			$ses_member_collections = substr( $ses_member_collections, 0, strlen($ses_member_collections) - 1 );			

			// the send mail by AWS is handled by its own handler in SendAWSEmail Controller class
			// reason : the queue cannot accept BIG DATA so we handle the big data in its own controller
			Queue::push('SendAWSEmail', array("newsletter_id" => $id, "member_collections" => $ses_member_collections), 'sesemail');
		}

		$list->already_sent = 1; // set the mail to already sent
		// update the status to Online because it sends already and it is sent
		$list->status = 1;
		$list->update();
	}

}
