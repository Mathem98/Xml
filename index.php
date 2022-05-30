<html>
    <head>
        <title>Notes</title>
        <meta charset="UTF-8">
       

       <style>
           h1,h3,h5 {
               font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
           }
           table, td {
               background-color:coral;
               margin-right:0px;
               float:left;
               padding:5px;
               margin:5px;
			   width:200px;
			   word-break: break-all;
			   
			  
           }
           td {
               color:darkblue;
               font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
           }
           label,input {
               font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
           }
		   #birsanjeid {
			font-size:14px;
		   }
		   .error {
		   color:#D5665E;
		   clear:both;
		   }
		   fieldset {
			   width:400px;
		   }
		   
			.button {
                display: inline-block;
                padding: 7px 15px;
                text-align: center;
                text-decoration: none;
                color: #ffffff;
                background-color: #C15704;
                border-radius: 6px;
                outline: none;
            }
       </style>
    </head>
    <body>
        <h1>Vaš osobni dnevnik!</h1>
        <h3>Unesite vaše aktivnosti!</h3> 
        
        

        <?php 
            
             $xml=new DOMDocument('1.0');
             $xml->preserveWhiteSpace = false;
             $xml->formatOutput=true;
             $xml->load("notes.xml");
             $root=$xml->getElementsByTagName("aktivnosti")->item(0);
             $var=$xml->getElementsByTagName("aktivnost");
             $id=$var->length;
			 $err="";
			 
			 function updateOutput ($xml) {
				 $x=$xml->documentElement;
				 foreach ($x->childNodes AS $item) {
					echo "<table>";
					foreach($item->childNodes as $child) {
						print "<tr><td>".$child->nodeName . " : " . $child->nodeValue . "</td></tr>";
					}
					echo "</table>";					
				} 	
					// update vars
					  $var=$xml->getElementsByTagName("aktivnost");
					  $id=$var->length;
					// za bolji output
					   $str=$xml->saveXML();
					   $xml->formatedOutput=true;
					   $xml->preserveWhiteSpace = false;
					   $xml->loadXML($str);
					   $xml->saveXml();					  
					   $xml->save("notes.xml");
              				
				
			}
			if(isset($_POST['deleteall'])) {
					for ($i=$var->length-1; $i>=1;--$i) {
							$node=$var->item($i);
							$node->parentNode->removeChild($node);
						}
					}
					
              
			if(isset($_POST['delete']) && ($_POST['id']!=NULL)) {
				$brisiID=$_POST['id'];
				if($brisiID >0 && $brisiID<$id) {
					for ($i=$var->length; $i>=0;--$i) {
						if($i == $brisiID) {
							$node=$var->item($i);
							$node->parentNode->removeChild($node);
						}
					}
					
					for($i=$brisiID; $i<$var->length;$i++) {						
						$node=$var->item($i);
						$node->setAttribute("id",$i);
					}
              
				}				
				else
					$err="Krivi ID (primjer aktivnosti se ne briše!).";
				
			}
            
             if (isset($_POST['create']) && !empty($_POST['noteTitle']) && !empty($_POST['date'] && $_POST['desc'])) {
                $title=$_POST['noteTitle'];
                $date=$_POST['date'];
                $description=$_POST['desc'];
                
                
            
                $aktivnost=$xml->createElement("aktivnost");
                $aktivnost->setAttribute("id",$id);
                $root->appendChild($aktivnost);
                                

                $naslov=$xml->createElement("Naslov",$title);
                $aktivnost->appendChild($naslov);
            
                $datum=$xml->createElement("Datum",$date);
                $aktivnost->appendChild($datum);
    
                $opis=$xml->createElement("Opis",$description);
                $aktivnost->appendChild($opis);
    
               
                
        
        
				updateOutput($xml);
             
                
            }
            else {
                   updateOutput($xml);
                    echo "<br style=\"clear:both;\"><br/><p class=\"error\">Unesite sva polja sa zvjezdicom (*) za unos nove aktivnosti.</p>";
            }
            
			
      
            
        ?>
        <br style="clear:both;">
        <form action="index.php" method="POST">
             <fieldset>
			   <legend>Stvaranje:</legend>
        <label for="noteTitle">Naziv Aktivnosti: *</label>
         <input name="noteTitle" type="text" />
         <br/> <br/>
         <label for="date">Datum: *</label>
         <input name="date" type="date" />
         <br/><br/>
         <label for="desc">Kratki opis: *</label> <br/>
         <textarea name="desc" rows="5" cols="25"></textarea>

         <br/><br/>
         <input type="submit" name="create" value="Stvori novu aktivnost">
		  </fieldset>
         <br/>
		 <?php echo"<span class='error'>".$err."</span>"; ?>
		 <fieldset>
		   <legend>Brisanje:</legend>
		 <label for="id" id="birsanjeid">ID aktivnosti (Vidljivi u xml datoteci): </label>
         <input name="id" type="number" />
		 <br/> <br/>
         <input type="submit" name="delete" value="Izbriši">
		 <input type="submit" name="deleteall" value="Izbriši sve">
		 </fieldset>
         </form>   
		 
		 <h5>Sve Vaše aktivnosti spremaju se u XML datotektu "notes.xml".</h5>
		 <a class="button" href=<?php echo"notes.xml?v=$var->length".".".rand(1,100);?> target="_blank">Otvori XML daotetku.</a>

    </body>
</html>
