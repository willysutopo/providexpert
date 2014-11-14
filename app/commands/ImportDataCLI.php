<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImportDataCLI extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'import:data';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

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
		$arguments = $this->argument();
		$target_folder = $arguments["filename"];
		
		if ($handle = opendir('public/compiled_files/'.$target_folder)) {

		    while (false !== ($filename = readdir($handle))) {

		        if ($filename != "." && $filename != "..") {

		        	if(strpos($filename,".csv")!==false && $filename!="")
		        	{
		        		$contents = File::get('public/compiled_files/'.$target_folder.'/'.$filename);

		            	$separator = (strpos($contents,";")!==false) ? ";" : ",";

						$this->insert_to_database($contents,$filename,$separator);	
		        	}
		        }
		    }

		    closedir($handle);
		}
		
	}

	public function check_existed($email)
	{
		$retval = Members::where('email', 'LIKE', trim($email))->count();

		return $retval;
	}

	public function insert_to_database($contents,$filename,$separator)
	{
		$this->line("\r\n");
		$this->line("\r\n");
		$this->line("\r\n");
		$this->line("===Add new members for kejora from ".$filename." ===\r\n");
		$this->line("\r\n");
		$this->line("Processing now..\r\n");
		$this->line("\r\n");

		$arr_contents = explode("\n",$contents);
		//echo "<pre>"; print_r($arr_contents);exit;
		for($i=1;$i<count($arr_contents);$i++)
		{	
			$row_content = $arr_contents[$i];
			if($row_content!="")
			{
				$arr_columns = explode($separator,str_replace('"','',$row_content));
				echo "<pre>"; print_r($arr_columns);exit;
				if((count($arr_columns)==14 || count($arr_columns)==15) && isset($arr_columns[0]) && isset($arr_columns[1]) && isset($arr_columns[2]) && isset($arr_columns[3]))
				{
					$full_name = $arr_columns[0];
					if($full_name)
					{
						if(strpos($full_name," ")!==false)
						{
							list($first_name,$last_name) = explode(" ",$full_name);	
						}else{
							$first_name = $full_name;
							$last_name = "";
						}
						
					}else{
						$first_name = $arr_columns[1];
						$last_name = $arr_columns[2];					
					}

					$email = trim(rtrim($arr_columns[3]));
					$location = $arr_columns[4];
					$company = $arr_columns[5];
					$job = $arr_columns[6];
					$mobile = $arr_columns[7];
					$source = $arr_columns[8];
					$category = $arr_columns[9];
					$is_momo = $arr_columns[10];
					$facebook = $arr_columns[11];
					$twitter = $arr_columns[12];
					$gtalk = $arr_columns[13];
					$linkedin = $arr_columns[14];


					//get the ids from excel raw data
					//
					//1.define the location id, ONLY IF the location in excel is not empty
					if($location!="")
					{
						$arr_query = Settings::where('key', 'LIKE', $location)->get(array('id'));
					}else{
						//else by default set the location with Indonesia
						$arr_query = Settings::where('key', 'LIKE', "indonesia")->where('group', 'LIKE', "location")->get(array('id'));
					}

					$location_id = $arr_query[0]->id;

					//2.define the source id
					if($source!="")
					{
						$arr_query = Settings::where('key', 'LIKE', $source)->where('group', 'LIKE', "sources")->get(array('id'));
						if(isset($arr_query[0]))
						{
							$source_id = ";".$arr_query[0]->id.";";		
						}else{
							$source_id = "";
						}
						
					}else{
						$source_id = "";
					}
					
					
					//3.define the category id
					if($category!="")
					{
						if(strpos($category,",")!==false)
						{
							$search_keyword = " (`key` like '%".str_replace(",","%' OR `key` like  '%",$category)."%') AND `group` like 'category'";
							$arr_category_lists = DB::select('select * from settings where '.$search_keyword);
							$category_ids = ";";

							foreach($arr_category_lists as $key=>$category)
							{
								$category_ids .= $category->id.";";
							}
						}else{
							$arr_query = Settings::where('key', 'LIKE', $category)->where('group', 'LIKE', "category")->get(array('id'));
							if(isset($arr_query[0]))
							{
								$category_ids = ";".$arr_query[0]->id.";";
							}else{
								$category_ids = 0;
							}

						}
													
					}else{
						$category_ids = 0;
					}
					
					//4.defining the default value for momo
					//by default, is momo is zero, if so, then check if the read file is from momo, then is momo changed to 1
					if($is_momo < 1)
					{
						$is_momo = strpos($filename,"momo")!==false ? 1 : 0 ;	
					}
					

					//before insert to database, we check for duplicate entry from each files
					$count_existed = $this->check_existed($email);

					//if not duplicate, proceed inserting data to database
					
					if($count_existed<=0 && $first_name!="" && $last_name!="" && $email!="")
					{
						$members = new Members;
						$members->first_name = $first_name;
						$members->last_name = $last_name;
						$members->email = trim(rtrim($email));
						$members->location_id = $location_id;
						$members->company = $company;
						$members->job = $job;
						$members->mobile = $mobile;				
						$members->source_ids = $source_id;
						$members->category_ids = $category_ids;
						$members->facebook_acc = $facebook;
						$members->twitter_acc = $twitter;
						$members->gtalk_acc = $gtalk;
						$members->linkedin_acc = $linkedin;
						$members->is_email_verified = 0;
						$members->is_momo = $is_momo;
						$members->save();

						$this->line("\r\n");
						$this->line("Inserting New Member: $first_name $last_name to database..\r\n");
						$this->line("\r\n");
						$this->line("\r\n");
					}else{

					}
				}
				
			}
		}
		$this->line("\r\n");
		$this->line("\r\n");
		$this->line("Done inserting Members from $filename");
		$this->line("\r\n");
		$this->line("\r\n");
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		 return array(
            array('filename', InputArgument::REQUIRED, 'define your excel file'),
        );
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
