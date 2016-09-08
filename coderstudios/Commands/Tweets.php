<?php

namespace CoderStudios\Commands;

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
				if ($tweet->name == 'Free Photos') {

					$images[] = file_get_contents(public_path() . '/images/kitten-2-21-08-2016.jpg');
					$images[] = file_get_contents(public_path() . '/images/beach-18-08-2016.jpg');
					$images[] = file_get_contents(public_path() . '/images/tom-1-24-08-2016.jpg');

					$result = $this->twitter->query('media/upload', 'POST', ['media' => $images[mt_rand(0,count($images)-1)]],true);
					$result = $this->twitter->post('statuses/update', ['media_ids' => $result->media_id, 'status' => $tweet->tweet, 'format' => 'json']);
				} else {
					$result = $this->twitter->post('statuses/update', ['status' => $tweet->tweet, 'format' => 'json']);
				}
			}
		}
	}
}
