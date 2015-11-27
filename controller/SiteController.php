<?php

class SiteController extends Controller {

	public function __construct()  {

	}

	public function index() {
		$this->render("index");
	}

	public function about(){
		$this->render("about");
	}

	public function skills(){
		$this->render("skills");
	}

	public function projects(){
		$this->render("projects");
	}

	public function contact(){
		$this->render("contact");
	}
}


