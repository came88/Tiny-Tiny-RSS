<?php
class FeedItem_RSS {
	private $elem;
	private $xpath;

	function __construct($elem, $doc, $xpath) {
		$this->elem = $elem;
		$this->xpath = $xpath;
	}

	function get_id() {
		$id = $this->elem->getElementsByTagName("guid")->item(0);

		if ($id) {
			return $id->nodeValue;
		} else {
			return $this->get_link();
		}
	}

	function get_date() {
		$pubDate = $this->elem->getElementsByTagName("pubDate")->item(0);

		if ($pubDate) {
			return strtotime($pubDate->nodeValue);
		}
	}

	function get_link() {
		$link = $this->elem->getElementsByTagName("link")->item(0);

		if ($link) {
			return $link->nodeValue;
		}
	}

	function get_title() {
		$title = $this->elem->getElementsByTagName("title")->item(0);

		if ($title) {
			return $title->nodeValue;
		}
	}

	function get_content() {
		$content = $this->elem->getElementsByTagName("description")->item(0);

		if ($content) {
			return $content->nodeValue;
		}
	}

	function get_description() {
		$summary = $this->elem->getElementsByTagName("description")->item(0);

		if ($summary) {
			return $summary->nodeValue;
		}
	}

	// todo
	function get_comments_url() {

	}

	function get_comments_count() {
		$comments = $this->xpath->query("slash:comments", $this->elem)->item(0);

		if ($comments) {
			return $comments->nodeValue;
		}
	}

	function get_categories() {
		$categories = $this->elem->getElementsByTagName("category");
		$cats = array();

		foreach ($categories as $cat) {
			array_push($cats, $cat->nodeValue);
		}

		$categories = $this->xpath->query("dc:subject", $this->elem);

		foreach ($categories as $cat) {
			array_push($cats, $cat->nodeValue);
		}

		return $cats;
	}

	function get_enclosures() {
		$enclosures = $this->elem->getElementsByTagName("enclosure");

		$encs = array();

		foreach ($enclosures as $enclosure) {
			$enc = new FeedEnclosure();

			$enc->type = $enclosure->getAttribute("type");
			$enc->link = $enclosure->getAttribute("url");
			$enc->length = $enclosure->getAttribute("length");

			array_push($encs, $enc);
		}

		$enclosures = $this->xpath->query("media:content", $this->elem);

		foreach ($enclosures as $enclosure) {
			$enc = new FeedEnclosure();

			$enc->type = $enclosure->getAttribute("type");
			$enc->link = $enclosure->getAttribute("url");
			$enc->length = $enclosure->getAttribute("length");

			array_push($encs, $enc);
		}

		return $encs;
	}

	function get_author() {
		$author = $this->elem->getElementsByTagName("author")->item(0);

		if ($author) {
			$name = $author->getElementsByTagName("name")->item(0);

			if ($name) return $name->nodeValue;

			$email = $author->getElementsByTagName("email")->item(0);

			if ($email) return $email->nodeValue;
		}

		$author = $this->xpath->query("dc:creator", $this->elem)->item(0);

		if ($author) {
			return $author->nodeValue;
		}

	}
}
?>
