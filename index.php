<?php
	class DomDocumentParser {

		private $doc;

		public function __construct($url) {

			$options = array(
				'http'=>array('method'=>"GET", 'header'=>"User-Agent: Doodle Search/0.1\n")
				);
			$context = stream_context_create($options);

			$this->doc = new DomDocument();
			@$this->doc->loadHTML(file_get_contents($url, false, $context));
		}

		private function getJobs() {
			$finder = new DomXPath($this->doc);
			$classname="row";
			$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
			return $nodes;
		}

		public function getElements(){

			$elementos = $this->getJobs();

			$keywords = array();
			$tmp_dom = new DOMDocument(); 
			foreach ($elementos as $node) 
			{
			    $tmp_node = $tmp_dom->importNode($node,true);
			    foreach ($tmp_node->childNodes as $el){
			    	if ($el->nodeName == "div"){
						foreach ($el->childNodes as $el2){
							if ($el2->nodeName == "a"){
								foreach ($el2->childNodes as $el3){
									if ($el3->nodeName == "h2"){
										if ($el3->nodeValue != ""){ echo $el3->nodeValue; }
										echo " - ";
									}
									if ($el3->nodeName == "p"){
										if ($el3->nodeValue != ""){ echo $el3->nodeValue; }
										echo "<br><br>";
									}
								}
							}
						}
					}
			    }
			}

			return($keywords);
		}
	}
	
	$limite = 781;

	for ($i = 0; $i < $limite; $i++){
		if ($i == 0){
			$implements = new DomDocumentParser('http://empregacampinas.com.br/categoria/vaga/');
			$implements->getElements();
		} else {
			$implements = new DomDocumentParser('http://empregacampinas.com.br/categoria/vaga/page/'.($i+1).'/');
			$implements->getElements();
		}
	}
?>