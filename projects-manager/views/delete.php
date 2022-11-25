<?
// the current object is linked elsewhere if (and only if?) it 
// exists in the tree (returned by $oo->traverse(0)) multiple times
$all_paths = $oo->traverse(0);
$l = 0; // is this declaration necessary?
$is_linked = false;
foreach($all_paths as $p) 
{
	if(end($p) == $uu->id)
	{
		// break when second link is found
		// no need to cycle through entire tree
		if($l) 
		{
			$is_linked = true;
			break;
		}
		else
			$l++; 
	}
}
?><main id="body-container"><?
	if(!$rr->action || strtolower($rr->action) != "delete") 
	{
		// if this object does not exist elsewhere in the tree,
		// check to see if its descendents are linked elsewhere
		// (or will be deleted with the deletion of this object)
		if(!$is_linked || !empty($dep_paths))
		{
			$all_paths = $oo->traverse(0);
			$dep_paths = $oo->traverse($uu->id);
			$dep_prefix = implode("/", $uu->ids)."/";
			$dp_len = strlen($dep_prefix);
			$dep = array(); // ids only
			$all = array(); // ids only
		
			foreach($dep_paths as $p)
				$dep[] = end($p);
		
			// compare the beginning of $each path $p to $dep_prefix
			// will that work?
			foreach($all_paths as $p)
				if(!(substr(implode("/", $p), 0, $dp_len) == $dep_prefix))
					$all[] = end($p);
		
			$dependents = array_diff($dep, $all);
			$k = count($dependents);
		}
		// this code is duplicated in:
		// + link.php
		// + add.php
		
		// display warning
		$sections = $oo->children($item['id']);
		if(!empty($section) && count($sections) == 1)
		{
			$msg = 'This Section is the only one of the project. Therefore it cannot be deleted.';
			?><p><? echo $msg; ?></p><div class="button-container"><input 
				type='button'
				name='cancel' 
				value='Go back' 
				class="btn on-grey"
				onClick="<? echo $js_back; ?>"
			></div><?
		}
		else
		{
			if($is_linked)
			{ 
				$msg = 'This Object is linked elsewhere, so the original will not be deleted.';
			}
			else
			{
				$msg = 'Warning! You are about to permanently delete this ';
				$msg .= empty($section) ? 'Project.' : 'Section. The project and other sections of it will NOT be deleted.';
			}
			?><p><? echo $msg; ?></p><form action="<? echo $admin_path.'delete/'.$uu->urls(); ?>" method="post" >
				<div class="button-container">
					<input
						type='hidden'
						name='action'
						value='delete'
					>
					<input 
						type='button'
						name='cancel' 
						value='Cancel' 
						class="btn on-grey"
						onClick="<? echo $js_back; ?>"
					> 
					<input
						type='submit' 
						name='submit'  
						class="btn on-grey"
						value='Delete Object'
					>
				</div>
			</form><?
		}
	}
	// processs form
	else
	{
		//  get wire that goes to this object to be deleted
		if (sizeof($uu->ids) < 2) 	
			$fromid = 0;
		else
			$fromid = $uu->ids[sizeof($uu->ids) - 2];
		$message = $ww->delete_wire($fromid, $uu->id);
		// if object doesn't exist anywhere else, deactivate it
		if(!$is_linked)
			$oo->deactivate($uu->id);
		?><div class="self"><? echo $message; ?></div><?
	}
	?>
</main>