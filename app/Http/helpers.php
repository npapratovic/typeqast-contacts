<?php

 
	function goDie($input = null)
	{
		if ($input != null)
		{
			// Start HTML definition (HTML5, utf-8, with Normalize.css and basic styling)
			echo '<!DOCTYPE html><html><head><meta charset="utf-8"><link rel="stylesheet" src="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.min.css"><style>body{width:100%;background-color:#fff;margin:0;padding:0;} p{margin:0 0 20px;padding:5px 10px;background-color:#eee;color:#333;} body p:first-child {background-color:#4BA2B5;} pre{padding:10px;border:1px solid #ddd;background-color:#eee;margin: 0 20px 20px;}</style><title>Temp output</title></head><body>';

			// $input is not null, check if it's an object, array or neither
			if (is_object($input))
			{
				// $input is object; check if has a links() method indicating Laravel pagination
				if (method_exists($input, 'links'))
				{
					// $input has links() method; use foreach to print out members of object
					echo '<p>Input is <strong>object</strong> and <strong>has pagination</strong>, adapting output.</p>';
					foreach ($input as $elementKey => $element)
					{
						echo '<pre>['. $elementKey .'] => '; print_r($element); echo '</pre>';
					}
				}
				else
				{
					// $input has no links() method
					echo '<p>Input is object and <strong>has no pagination</strong>, adapting output.</p><pre>'; print_r($input); echo '</pre>';
				}
			}
			elseif (is_array($input))
			{
				// $input is an array
				echo '<p>Element is an <strong>array</strong>, adapting output.</p>';
				foreach ($input as $elementKey => $element)
				{
					// check if element of array is object
					// (to handle status + Eloquent model or Query Builder result)
					if (is_object($element))
					{
						// $element is object; check if has a links() method indicating Laravel pagination
						if (method_exists($element, 'links'))
						{
							// $element has links() method; use foreach to print out members of object
							echo '<p>Element is <strong>object</strong> and <strong>has pagination</strong>, adapting output. Key: [' . $elementKey . ']</p>';
							foreach ($element as $subElementKey => $subElement)
							{
								echo '<pre>['. $subElementKey .'] => '; print_r($subElement); echo '</pre>';
							}
						}
						else
						{
							// $element has no links() method
							echo '<p>Element is <strong>object</strong> and <strong>has no pagination</strong>, adapting output.</p><pre>'; print_r($element); echo '</pre>';
						}
					}
					else
					{
						// $element is not an object or an array
						echo '<p>Element is <strong>not object nor an array</strong>, adapting output.</p><pre>[' . $elementKey . '] => '; print_r($element); echo '</pre>';
					}
				}
			}
			else
			{
				// $input is not an object or an array
				echo '<p>Input is <strong>not object nor an array</strong>, adapting output.</p><pre>'; print_r($input); echo '</pre>';
			}

			// End HTML definition
			echo '</body></html>';
		}
		die;
	}


