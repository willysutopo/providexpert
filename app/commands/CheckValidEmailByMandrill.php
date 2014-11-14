<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CheckValidEmailByMandrill extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'email:checkvalidemailbymandrill';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Check Regularly Whether An Email Is Valid ( via Mandrill method )';

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
		$members = DB::table('members')
		->join('newsletter_log', 'newsletter_log.email', '=', 'members.email')
		->where('members.is_email_verified', 0)
		->where('newsletter_log.source', 'mandrill')
		->select('members.email')
		->skip(0)->take(20)->get();
		$mandrill_key = Config::get('thirdparty_mail_settings.mandrill_key');

		$mandrill = new Mandrill($mandrill_key);

		// content header
		$content = "EMAIL CHECKING STATUS BY MANDRILL AT " . date("Y-m-d H:i:s") . "\n";
		$content .= "==================================================================\n";
		$file_path = storage_path().'/logs/email.log';

		File::append($file_path, $content);

		if ( empty($members) )
			exit;

		foreach( $members as $member )
		{
			echo $member->email;
			echo '\n';

			$email = $member->email;
			$query = 'full_email:'.$email;
			$date_from = '2014-10-01'; // starting date : set to 1 October 2014
			$date_to = date("Y-m-d"); // current date
			$tags = array();

			$senders = array();
			$api_keys = array($mandrill_key);
			$limit = 100;
			$result = $mandrill->messages->search($query, $date_from, $date_to, $tags, $senders, $api_keys, $limit);

			// if result is not empty
			if ( !empty( $result ) )
			{
				foreach( $result as $detail )
				{
					// if the state is rejected, don't process it anymore
					if ( $detail['state'] == 'rejected' )
					{
						// format of data
						$content = "EMAIL : " . $email . "'s STATUS IS : " . $detail['state'] . " AT : " . date("Y-m-d H:i:s", $detail['ts']) . "\n";
						$file_path = storage_path().'/logs/email.log';

						File::append($file_path, $content);

						// change the status of member to rejected
						$rejected_member = Members::where('email', $email)->update(array('is_email_verified' => 2));

						break;
					}
					else
					// if the state is sent, we have to check again for the following conditions
					if ( $detail['state'] == 'sent' )
					{
						// if the email has ever been opened or a link in the email has ever been clicked
						if ( $detail['opens'] > 0 || $detail['clicks'] > 0 )
						{
							// format of data
							$content = "EMAIL : " . $email . "'s STATUS IS : " . $detail['state'] . " AT : " . date("Y-m-d H:i:s", $detail['ts']) . "\n";
							$file_path = storage_path().'/logs/email.log';

							File::append($file_path, $content);

							// change the status of member to verified
							$rejected_member = Members::where('email', $email)->update(array('is_email_verified' => 1));

							break;
						}
					}
					else
					// if the state is bounced, we have to check again for the following conditions
					if ( $detail['state'] == 'bounced' || $detail['state'] == 'soft-bounced' )
					{
						// if the bounce status is bad mailbox or invalid domain
						if ( $detail['bounce_description'] == 'bad_mailbox' || $detail['bounce_description'] == 'invalid_domain' )
						{
							// format of data
							$content = "EMAIL : " . $email . "'s STATUS IS : " . $detail['state'] . " AND BOUNCE STATUS IS : " . $detail['bounce_description'] . " AT : " . date("Y-m-d H:i:s", $detail['ts']) . "\n";
							$file_path = storage_path().'/logs/email.log';

							File::append($file_path, $content);

							// change the status of member to rejected
							$rejected_member = Members::where('email', $email)->update(array('is_email_verified' => 2));

							break;
						}
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
