<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Mailgun\Mailgun;

class CheckValidEmailByMailgun extends Command {
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'email:checkvalidemailbymailgun';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Check Regularly Whether An Email Is Valid ( via Mailgun method )';

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
		// get all members' emails whose status is not verified yet
		$members = Members::where('is_email_verified', 0)->get();
		$mailgun_key = Config::get('thirdparty_mail_settings.mailgun_key');

		$mailgun = new Mailgun($mailgun_key);

		// content header
		$content = "EMAIL CHECKING STATUS BY MAILGUN AT " . date("Y-m-d H:i:s") . "\n";
		$content .= "==================================================================\n";
		$file_path = storage_path().'/logs/email.log';

		File::append($file_path, $content);

		if ( empty($members) )
			exit;

		foreach( $members as $member )
		{
			$email = $member->email;
			$mgClient = new Mailgun($mailgun_key);
			$domain = 'kejorahq.com';
			$queryString = array(
				//'begin'        => $begin . " +0700",
				//'end'		   => $end . " +0700",
				'ascending'    => 'no',
				'limit'        =>  100,
				'pretty'       => 'yes',
				'recipient'	   => $email
			);

			# Make the call to the client.
			$result = $mgClient->get("$domain/events", $queryString);
			$items = $result->http_response_body->items;

			if ( !empty( $items ) )
			{
				foreach ( $items as $item )
				{
					// if the state is rejected, don't process it anymore
					if ( $item->event == 'rejected' || $item->event == 'failed' )
					{
						// format of data
						$content = "EMAIL : " . $email . "'s STATUS IS : " . $item->event . " SEVERITY : " . $item->severity . "\n";
						$file_path = storage_path().'/logs/email.log';

						File::append($file_path, $content);

						if ( $item->severity == "permanent" )
						{
							// change the status of member to rejected
							$rejected_member = Members::where('email', $email)->update(array('is_email_verified' => 2));
						}

						break;
					}
					else
					// if the state is sent, we have to check again for the following conditions
					if ( $item->event == 'opened' || $item->event == 'clicked' || $item->event == 'delivered' )
					{
						// format of data
						$content = "EMAIL : " . $email . "'s STATUS IS : " . $item->event . "\n";
						$file_path = storage_path().'/logs/email.log';

						File::append($file_path, $content);

						// change the status of member to verified
						$rejected_member = Members::where('email', $email)->update(array('is_email_verified' => 1));

						break;
					}
				}
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
		return array(
			
		);
	}

}
