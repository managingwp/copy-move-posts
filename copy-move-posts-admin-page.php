<script type="text/javascript">
	if(typeof jQuery == 'undefined')
	{
	    document.write('<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></'+'script>');
	}
	jQuery(document).ready(function ($) {
	    $("#convert_post").submit(function () {
	    	var isnum = /^\d+$/;
	    	var need=$('#needCount').val();
	    	if(!isnum.test(need))
	    	{
	    		if(need==-1)
	    		{
	    			return true;
	    		}
	    		else if(need<-1)
	    		{
	    			alert("Please enter value bigger than -1.");
	    			$('#needCount').val(-1);
	    			return false;
	    		}
	    		else
	    		{
	    			alert("Please enter only numeric value.");
	    			$('#needCount').val(-1);
	    			return false;
	    		}
	    	}
	        $(".submitBtn").css("display", 'none');
	        $(".sbt_text").css("display", 'block');
	        return true;
	    });
	});
</script>
<style type="text/css">
	.sbt_text{display: none;}
</style>
<div>
	<form method="post" action='' id="convert_post">
		<div class="what_want">
			<h3>What you  want to do with Posts?</h3>
			<input type="radio" name="cmp" checked="checked" value="1">Copy
			<span><b>&nbsp;&nbsp;OR&nbsp;&nbsp;</b></span>
			<input type="radio" name="cmp" value="2">Move
		</div><br>
		<div class="post_type">
		<span><b>From:</b></span>
		<?php $post_type=apply_filters('CPMV_get_posts_types',''); //echo "<pre>"; print_r($post_type);?>
		<select name="post_from">
			<?php foreach ($post_type as $types) { ?>
				<option value="<?php echo $types->name;?>"><?php echo $types->labels->name?> (<?php echo $types->name;?>)</option>
			<?php }?>
		</select>
		<span><b>To:</b></span>
		<select name="post_to">
			<?php foreach ($post_type as $types) { ?>
				<option value="<?php echo $types->name;?>"><?php echo $types->labels->name?> (<?php echo $types->name;?>)</option>
			<?php }?>
		</select>
		</div><br>
		<span><b>Enter post count that you want to copy or move:</b></span>
		<input type="text" name="need_count" id="needCount" value="-1"><span> -1 means all posts.</span>

		<br>
		<br>
		<input type="submit" value="Complete" class="button-primary submitBtn" name="change_post">
		<p class="sbt_text">Please wait, While Processing...</p>
		<br><br>
		<b>Note:-</b> Please take the database backup first then try.
		<br>
		<br>
		Please rate and review it <a href="https://wordpress.org/support/plugin/copy-move-posts/reviews/" target="_blank">wordpress.org</a> to spread the love.
	</form>
</div>
