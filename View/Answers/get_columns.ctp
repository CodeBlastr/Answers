<?php
echo $this->Form->label('Select an ID');
echo $this->Form->select('propertySelect', $properties, array('required' => true, 'empty' => false));