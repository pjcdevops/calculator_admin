<?php
echo '<div class="back_menu">';
echo '<ul class="nav nav-tabs nav-stacked">';
echo '<li>' . $this->Html->link('Back',
                                array('action' => 'index', $table),
                                array('escape' => false)) . '</li>';
echo '</ul>';
echo '</div>';
echo '<div class="form_container">';
	echo '<h2>Edit ' . Inflector::Singularize($desc) . '</h2>';
	echo $this->Form->create($model_name);
	echo $this->Form->inputs($form_fields, 
							 array('created', 
							 	   'modified', 
							 	   'updated',
							 	   'dt_added'
							 	   ), 
							 array('legend' => false));
	echo $this->Form->submit('Save', 
		  				  	  array('class' => 'btn btn-primary'));
	echo $this->Form->end(); 
echo '</div>';
