<?php

namespace Pagewiser\Idn\Client;

class Api
{

	/**
	 * @const Fits in given area, dimensions are less than or equal to the required dimensions
	 */
	const FIT = '';

	/**
	 * @const Shrinks images
	 */
	const SHRINK_ONLY = 'c';

	/**
	 * @const Stretch image and ignore aspect ratio
	 */
	const STRETCH = 's';

	/**
	 * @const Fills given area, dimensions are greater than or equal to the required dimensions
	 */
	const FILL = 'f';

	/**
	 * @const Fills given area exactly, crop the image
	 */
	const EXACT = 'e';

	/**
	 * IDN API url
	 *
	 * @var string $apiUrl IDN API url
	 */
	private $apiUrl = '';

	/**
	 * IDN image url
	 *
	 * @var string $imgUrl IDN image url
	 */
	private $imgUrl = '';

	/**
	 * Client UID
	 *
	 * @var string $client Client UID
	 */
	private $client;

	/**
	 * Password
	 *
	 * @var string $password Password
	 */
	private $password;


	/**
	 * Prepare the client API
	 *
	 * @param string $client Client UID
	 * @param string $password Password
	 */
	public function __construct($client, $password)
	{
		$this->client = $client;
		$this->password = $password;
	}


	public function setApiUrl($url)
	{
		$this->apiUrl = $url;
	}


	public function setImageUrl($url)
	{
		$this->imageUrl = $url;
	}


	private function curl($post)
	{
		$postData = array(
			'client' => $this->client,
			'password' => $this->password,
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post + $postData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close ($ch);

		return json_decode($result);
	}


	public function uploadUrl($path, $fileName, $fileUrl)
	{
		$content = file_get_contents($fileUrl);
		$file = realpath($filePath);
		$post = array(
			'action' => 'upload',
			'path' => $path,
			'filename' => $fileName,
			'content' => $content,
		);

		return $this->curl($post);
	}


	public function upload($path, $fileName, $filePath)
	{
		$file = realpath($filePath);
		$post = array(
			'action' => 'upload',
			'path' => $path,
			'filename' => $fileName,
			'content' => '@'.$file,
		);

		return $this->curl($post);
	}


	public function delete($path, $fileName)
	{
		$post = array(
			'action' => 'delete',
			'path' => $path,
			'filename' => $fileName,
		);

		return $this->curl($post);
	}


	public function clean($path, $fileName)
	{
		$post = array(
			'action' => 'clean',
			'path' => $path,
			'filename' => $fileName,
		);

		return $this->curl($post);
	}


	public function purge()
	{
		$post = array(
			'action' => 'purge',
		);

		return $this->curl($post);
	}


	public function image($path, $fileName, $size = 'full', $transformation = self::FIT)
	{
		return $this->imageUrl . '/' . $this->client . '/' . $size . $transformation . '/' . $path . '/' . $fileName;
	}


}
