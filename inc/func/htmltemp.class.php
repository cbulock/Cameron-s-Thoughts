<?php
/*/////////////////////////////////////
HTML Template Class
Created by Cameron Bulock
Version 0.2 Released 03/30/2007
///////////////////////////////////////

Variables

$this->ml - Markup language.  xhtml and html are valid values
$this->mlver - Markup language version.  Current support will be 4.0 for HTML and 1.0 for XML.  Planned support for XHTML 2.0.

$this->htmlattrib - allows setting HTML tag attributes without directly calling the tag

///////////////////////////////////////
Layout of $body:
Array
(
    [0] => Array
        (
            [tag] => img
            [attrib] => Array
                (
                    [src] => /images/test.png
                    [alt] => Alternate Text
                )

        )

    [1] => Array
        (
            [tag] => p
	    [value] => Here is paragraph text
            [attrib] => Array
                (
                    [lang] => US-en
                )
        )

)
/////////////////////////////////////*/

class HTMLTemp
{

	var $version = '0.2'; //Class version info
	
	var $head; //holds the actual heading content
	var $body; //holds the actual body content
	

	function lang($lang)
	{
		switch ($this->ml)
		{
			case 'html':
				return " lang='".$lang."'";
			break;
			case 'xhtml':
				return " xml:lang='".$lang."'";
			break;
		}
	}

	function attrib_handler($attribs)
	{
		$text = '';
		if ($attribs) foreach($attribs as $key => $attrib)
		{
			if ($key == 'lang')
			{
				$text .= $this->lang($attrib);
			}
			else
			{
				$text .= ' '.$key.'="'.$attrib.'"';
			}
		}
		return $text;
	}
	
	function addToHead($content)
	{
		$this->head .= $content;
	}
	
	
	function addToBody($content)
	{
		$this->body .= $content;
	}
	
	function outputPage($echo=TRUE)
	{
		$final = $this->docType();
		$final .= $this->html();
		$final .= "<head>\n";
		$final .= $this->head;
		$final .= "</head>\n";
		$final .= "<body>\n";
		$final .= $this->body;
		$final .= "</body>\n";
		$final .= "</html>";
		if ($echo)
		{
			echo $final;
		}
		return $final;
	}
	
	function outputPageSource()
	{
		$final = $this->docType('xhtml');
		$final .= $this->html(NULL, 'xhtml');
		$final .= "<head>\n";
		$final .= $this->head;
		$final .= "</head>\n";
		$final .= "<body>\n";		
		$final .= nl2br(htmlentities($this->outputPage(),ENT_QUOTES));
		$final .= "</body>\n";
		$final .= "</html>";
		return $final;
	}
	
/*///////////////////////////////////////////////////////////////////////////////////////////////
//
// HEAD TAGS
//
///////////////////////////////////////////////////////////////////////////////////////////////*/	

	function docType($forceml = NULL)
	{
		if ($forceml == NULL) $forceml = $this->ml;
		switch ($forceml)
		{
			case 'html':
				return "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">";
			break;
			case 'xhtml':
				return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
			break;
		}
	}
	
	function html($attrib = NULL, $forceml = NULL)
	{
		if (!$attrib) $attrib = $this->htmlattrib;
		if ($forceml == NULL) $forceml = $this->ml;
		$output = "<html";
		switch ($forceml)
		{
			case 'html':
					//nothing here yet
			break;
			case 'xhtml':
				$output .= " xmlns='http://www.w3.org/1999/xhtml'";
			break;
		}
		if ($attrib['lang']) $output .= $this->lang($attrib['lang']);
		$output .= ">\n";
		return $output;
	}

	function title($content)
	{
		$this->addToHead("<title>".$content."</title>\n");
	}

	function stylesheet($url,$title,$alt=FALSE)
	{
		$output = "<link rel='";
		if ($alt) $output .= "alternate ";
		$output .= "stylesheet' type='text/css' href='".$url."' title='".$title."' />\n";
		$this->addToHead($output);
	}

/*///////////////////////////////////////////////////////////////////////////////////////////////
//
// BODY TAGS
//
///////////////////////////////////////////////////////////////////////////////////////////////*/


	function heading($content, $type = "3", $attrib = NULL) //Will default to an H3 tag.  No reason why really, that just seems like a common tag
	{
		$output = "<h" . $type;
                $output .= $this->attrib_handler($attrib);
		$output .= ">" . $content . "</h" . $type . ">\n";
		$this->addToBody($output);
		//return $output;
	}
	
	function linktag($url, $content = NULL, $attrib = NULL)
	{
		if (!$content) $content = $url;
		$output = "<a href='".$url."'";
                $output .= $this->attrib_handler($attrib);
		$output .= ">".$content."</a>";
		//$this->addToBody($output);
		return $output;
	}

	function image($src, $alt, $url = NULL, $width = NULL, $height = NULL, $attrib = NULL)
	{
		$output = "<img src='" . $src . "' alt='". $alt . "'";
                $output .= $this->attrib_handler($attrib);
		switch ($this->ml)
		{
			case 'html':
				$output .= ">\n";
			break;
			case 'xhtml':
				$output .= " />\n";
			break;
		}
	}

	function text($content,$attrib = NULL)
	{
		$output = "<p";
		$output .= $this->attrib_handler($attrib);
		$output .= ">".$content."</p>\n";
		$this->addToBody($output);
		//return $output;
	}

	function comment($content)
	{
		$output = "<!-- ".$content." -->\n";
		$this->addToBody($output);
		//return $output;
	}

}
