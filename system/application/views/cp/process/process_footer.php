	</div> <!-- #process_content -->
</div> <!-- #process_container -->


<footer id="process_footer">

	<!--<h3>non-required reading</h1>
	<ul>
		<li>
			<a href="#">Article Header</a>
			<p>Lorum ipsum dolor set amit ipsulum spet amit honori debucle</p>
		</li>
		<li>
			<a href="#">Article Header</a>
			<p>Lorum ipsum dolor set amit ipsulum spet amit honori debucle</p>
		</li>
		<li>
			<a href="#">Article Header</a>
			<p>Lorum ipsum dolor set amit ipsulum spet amit honori debucle</p>
		</li>
	</ul>-->


</footer>
<div style="clear: both"></div>


<script>

 	$("#process_right ul li div").hide();
 	
    $("#process_right ul li").click(function (event) {
      
	     $(this).children('div').slideToggle('fast');
	     event.preventDefault();
	      
	     var imgCheck = $(this).children('a').children('img').attr('src');
	      
	     if(imgCheck == '/images/process/plus.png')
	     {
	     	$(this).children('a').children('img').attr('src', '/images/process/minus.png' );
	     	
	     }else if(imgCheck == '/images/process/minus.png')
	     {
	    	 $(this).children('a').children('img').attr('src', '/images/process/plus.png' );
	     }
     
    });
    
     $("#process_right ul li").mouseover(function (event) {
     
     	$(this).fadeIn().css("background-color", "#ffffff");
     });
     
     $("#process_right ul li").mouseout(function (event) {
     
     	$(this).fadeIn().css("background-color", "#eeeeee");
     });
</script>