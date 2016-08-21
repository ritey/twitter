<?php

namespace CoderStudios\Commands;

use Log;
use CoderStudios\Library\Tweets as TweetLibrary;
use Thujohn\Twitter\Twitter;

class Tweets {

	protected $tweets;

	public function __construct(TweetLibrary $tweets, Twitter $twitter)
	{
		$this->tweets = $tweets;
		$this->twitter = $twitter;
	}

	public function tweet()
	{

		$minutes = mt_rand(720,10080);

		$data = [
			'enabled' 		=> 1,
			'name' 			=> 'Free Photos',
			'tweet' 		=> 'FREE photos available to use as you wish http://its.io/photos #FREE #PHOTOS #HIRES #CATS #PLANES',
			'tweeted_at' 	=> date('Y-m-d H:i:s'),
			'next_at'		=> date('Y-m-d H:i:s',strtotime('+ '.$minutes.' minutes')),
			'created_at'	=> date('Y-m-d H:i:s'),
			'updated_at'	=> date('Y-m-d H:i:s'),
		];

		$date1 = date('Y-m-d H:i:s',strtotime('-3 minutes'));
		$date2 = date('Y-m-d H:i:s',strtotime('+3 minutes'));

		$tweets = $this->tweets->findNext($date1, $date2);

		if ($tweets->count()) {
			foreach($tweets as $tweet) {
				$data = [
					'tweeted_at' 	=> date('Y-m-d H:i:s'),
					'next_at' 		=> date('Y-m-d H:i:s', strtotime('+' . $minutes . ' minutes')),
				];
				$this->tweets->update($tweet->id, $data);
				$result = $this->twitter->post('statuses/update', ['status' => $tweet->tweet, 'format' => 'json']);
				//Log::info(print_r($result,true));
			}
		}
		//$this->tweets->add($data);

		//Log::info(print_r($this->tweets->all(),true));

	}
}
