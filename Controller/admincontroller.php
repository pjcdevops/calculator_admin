<?php
class AdminController extends AppController {

	public $paginate = array(
		
		'limit' => 10

		);


	// this will display the admin home page
	public function index($table=1) {

			switch ($table) {
				case 1:
					$model = 'Version';
					$desc = 'Car versions';
					break;
				case 2:
					$model = 'CarModel';
					$desc = 'Car models';
					break;
				case 3:
					$model = 'RvPercentage';
					$desc = 'Rv percentages';
				break;
				default: 
					$model = false;
					break;
			}

			if($model == false)
				return false;

			$this->loadModel($model);
			// pagination
			$this->paginate = array(
				'order' => array(
					$model.'id' => 'ASC',
					),
				'limit' => 20
				);

			$data = $this->paginate($this->$model);

			$this->set('data', $data);
			$this->set('desc', $desc);
			$this->set('model', $table);
	}

	

	// add action for all three tables
	public function add($table){

		switch ($table) {
                case 1:	
					$model = 'Version';
					$desc = 'Car versions';
					break;
				case 2:
					$model = 'CarModel';
					$desc = 'Car models';
					break;
				case 3:
					$model = 'RvPercentage';
					$desc = 'Rv percentages';
				break;
		}

		$this->loadModel($model);

		if($this->request->is('post')){

			$this->$model->create();
            
            $db = ConnectionManager::getDataSource('default');
            $this->request->data[$model]['dt_added'] = $db::expression('NOW()');
        
			if($this->$model->save($this->request->data)){
                $this->Session->setFlash('Data saved.', 'flash');
                $this->redirect(array('action' => 'index', $table));
            }
			else
				$this->Session->setFlash('Data could not be saved.', 'warning');
		}

				$db_fields = array_keys($this->$model->schema());
				 $this->set('form_fields', $db_fields);
				 $this->set('model', $model);
                 $this->set('desc', $desc);
                 $this->set('table', $table);
	}

	// edit action for all three tables
	public function edit($table, $id=null) {
        // When we are editing it is more helpful if the redirect after sucessful posting of the data to the server
        // redirects to the page we were originally on when we clicked edit. To facilitate this we store the url
        // in a session if the referering url is not the edit page itself and use this for the redirect
        if(strpos($this->referer(), 'edit') == false)
            $this->Session->write('Back.referer', $this->referer());

		switch ($table) {
				case 1:
					$model = 'Version';
					$desc = 'Car versions';
					break;
				case 2:
					$model = 'CarModel';
					$desc = 'Car models';
					break;
				case 3:
					$model = 'RvPercentage';
					$desc = 'Rv percentages';
				break;
			}
		 
		$this->loadModel($model);

		if($this->request->is('post') || $this->request->is('put')) {
			if($id != null){
				$this->$model->id = $id;
                $db = ConnectionManager::getDataSource('default');
                $this->request->data[$model]['dt_added'] = $db::expression('NOW()');
                if($this->$model->save($this->request->data) ){
                    $this->Session->setFlash('Data Updated.', 'flash');
                    $this->redirect($this->Session->read('Back.referer'));
                }
				else
					$this->Session->setFlash('Data could not be saved.', 'warning');
			}else
				$this->Session->setFlash('Invalid Edit id provided.', 'warning');
		}else{
				$entry = $this->$model->findById($id);
				$this->request->data = $entry;
                $db_fields = array_keys($this->$model->schema());
				$this->set('form_fields', $db_fields);
				$this->set('model_name', $model);
                $this->set('desc', $desc);
                $this->set('table', $table);
		}
			
	}

	// delete action for all three tables
	public function delete($table, $id=null){

		switch ($table) {
				case 1:
					$model = 'Version';
					break;
				case 2:
					$model = 'CarModel';
					break;
				case 3:
					$model = 'RvPercentage';
				break;
			}

		$this->loadModel($model);

		if($this->$model->delete($id))
	 	$this->Session->setFlash('Item deleted successfully', 'flash');
	 	
	 	$this->redirect($this->referer());
	}

	public function create_user(){

		$this->loadModel('User');

		if($this->request->is('post')){

			$this->User->create();

			if($this->User->save($this->request->data))
				$this->Session->setFlash('Registered successfully. Please await activation', 'flash');
			else
				$this->Session->setFlash('Could not register you at this time.', 'warning');
		}

	}

	public function login(){

		$this->loadModel('User');
		
	    if ($this->request->is('post')) {

	    	if($this->Auth->login())
	    		$this->redirect(array('action' => 'index'));
	    	else
	    	 $this->Session->setFlash('Username or password is incorrect', 'warning');
	    }

	}

	public function logout(){
		
		$this->Session->delete('User');
		$this->redirect($this->Auth->logout());	
	}
}
