<?php

namespace Drupal\azra\Form;
require('/opt/lampp/htdocs/azra/vendor/fzaninotto/faker/src/autoload.php');

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class RegForm extends FormBase {

  public function getFormId() {
    return 'azra_reg_form';
  }


  public function buildForm(array $form, FormStateInterface $form_state) {

    $faker = \Faker\Factory::create();

    $this->messenger()->addMessage($faker->name);



    if ($id) {
      $student = \Drupal::database()->query("select * from _students where id = $id")->fetchObject();
    }
    $form['firstname'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First name'),
      '#default_value' => $student->firstname,


    ];
    $form['middlename'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Middle name'),
      '#default_value' => $student->middlename,

    ];
    $form['surname'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Surname'),
      '#default_value' => $student->surname,

    ];


    $form['phonenumber'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone number'),
      '#default_value' => $student->phonenumber,

    ];
    $form['placeofbirth'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Place of birth'),
      '#default_value' => $student->placeofbirth,

    ];

    $form['levelofentry'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Level of entry'),
      '#default_value' => $student->levelofentry,

    ];

    $form['dateofbirth'] = [
      '#type' => 'date',
      '#title' => 'Date of Birth',
      '#default_value' => $student->dateofbirth,

    ];
    $form['gender'] = [
      '#type' => 'select',
      '#title' => 'Gender',
      '#default_value' => $student->gender,
      '#options' => ['-Select-' => '', 'Male' => 'Male', 'Female' => 'Female'],
    ];
    $form['maritalstatus'] = [
      '#type' => 'select',
      '#title' => 'Marital status',
      '#default_value' => $student->maritalstatus,
      '#options' => ['-Select-' => '', 'Single' => 'Single', 'Married' => 'Married'],
    ];
    $form['rrrnumber'] = [
      '#type' => 'textfield',
      '#default_value' => $student->rrrnumber,
      '#title' => $this->t('RRR number'),
    ];
    $form['amount'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Amount'),
      '#default_value' => $student->amount,

    ];
    $validators = [
      'file_validate_extensions' => array('jpg', 'png'),
      'file_validate_size' => [500000],
    ];

    $path =

    //$form['f1']['photo1'] = [ '#markup' => "<img src='$path' width='100' height='100' alt='Photo'/>" ];

      $form['photo'] = [
        '#type' => 'managed_file',
        '#name' => 'photo',
        '#title' => t('Passport Photo'),
        '#size' => 20,
        '#description' => t('JPG format only'),
        '#upload_validators' => $validators,
        '#upload_location' => 'public://photos/',
       // '#default_value' => isset($reg->photo) ? [$reg->photo] : '',
        //'#default_value' => array($reg->photo),
        //'#default_value' => array($reg->photo),
        // '#required' => TRUE,
        // '#default_value' => $this->get('photo'),

        // '#upload_location' => 'public://photos/',
        // '#upload_validators'=>  array('file_validate_name' => array()),
      ];


    $form['actions'] = [
      '#type' => 'actions',
    ];

    if ($id) {

      $form['id'] = ['#type' => 'hidden', '#value' => $id ];

      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Edit'),
      ];

    }
    else {
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
  }

    $form['actions']['demo'] = [
      '#type' => 'submit',
      '#value' => $this->t('Demo'),
    ];

    $form['actions']['generate'] = [
      '#type' => 'submit',
      '#value' => $this->t('Generate'),
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
     $title = $form_state->getValue('title');
    if (strlen($title) < 5) {
      // $form_state->setErrorByName('title', $this->t('The title must be at least 5 characters long.'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $faker = \Faker\Factory::create();

    $op = $form_state->getValue('op');

    if ($op == 'Submit') {

    $firstname = $form_state->getValue('firstname');
    $middlename = $form_state->getValue('middlename');
    $surname = $form_state->getValue('surname');
    $phonenumber = $form_state->getValue('phonenumber');
    $placeofbirth = $form_state->getValue('placeofbirth');
    $dateofbirth = $form_state->getValue('dateofbirth');
    $levelofentry = $form_state->getValue('levelofentry');
    $gender = $form_state->getValue('gender');
    $maritalstatus = $form_state->getValue('maritalstatus');
    $rrrnumber = $form_state->getValue('rrrnumber');
    $amount = $form_state->getValue('amount');
    $this->messenger()->addMessage("congratulation $surname $firstname $middlename you have successfully completed your registration");

 // SRTART PHOTO
 $fid = $form_state->getValue(['photo', 0]);
 $fields['photo'] = $fid;

 $photo = $fid;

 if(isset($fid)) {
   $fields['photo'] = $fid;

   // if (!$form_state->getErrors() && !empty($fid)) {
     $rrr = $uid;
     try {
       $file = File::load($fid);
       $file->setFilename($rrr);
       $file->save();

       // $host = \Drupal::request()->getHost();
       // \Drupal::logger('mannirigr_photo')->notice('<pre>' . print_r($host, TRUE) . '</pre>');

       try {
         $storage = new StorageClient(['projectId' => 'kanoecommerce']);
         $bucket = $storage->bucket('kanoecommerce.appspot.com');
         $image_path = file_url_transform_relative(file_create_url($file->getFileUri()));
         $image_path = ltrim($image_path, '/');
         $file2 = fopen($image_path, 'r');
         $object = $bucket->upload($file2, ['name' => "$rrr.jpg", 'predefinedAcl' => 'publicRead']);

       } catch (\Throwable $e) {
         \Drupal::logger('mannirigr_error')->notice('<pre>' . print_r($e, TRUE) . '</pre>');
       }


       // $file = File::load($fid);
       // exit($fid);
       $new_filename = $rrr.".jpg";
       // exit($new_filename);

       if (isset($new_filename)) {
         $stream_wrapper = \Drupal::service('file_system')->uriScheme($file->getFileUri());
         $new_filename_uri = "{$stream_wrapper}://photos/{$new_filename}";
         file_move($file, $new_filename_uri);
       }

       // exit('Moded');



     }
     catch (\Throwable $e) {
       // watchdog_exception('mannirtrs_photo', $e);
       // \Drupal::logger('mannirtrs_photo')->notice('<pre>' . print_r($e, TRUE) . '</pre>');

     }
   }

   // END PHOTO

// print('<pre>' . print_r($fields, TRUE) . '</pre>'); exit();



    $fields = [
      'photo' => $fid,
      'firstname' => $firstname,
      '$middlename' => $middlename,
      '$surname' => $surname,
      '$phonenumber' => $phonenumber,
      '$placeofbirth' => $placeofbirth,
      '$dateofbirth' => $dateofbirth,
      '$levelofentry' => $levelofentry,
      '$gender' => $gender,
      '$maritalstatus' => $maritalstatus,
      '$rrrnumber' => $rrrnumber,
      '$amount' => $amount,


    ];

      $query = \Drupal::database()->insert('_students');
      $query->fields($fields);
      $query->execute();

    }

    if ($op == 'Demo') {

      $student = [
      'firstname' => $faker->name,
      'middlename' => $faker->name,
      'surname' => $faker->lastname,
      'phonenumber' =>  $faker->numberBetween($min =2347000000, $max=2347999999),
      'placeofbirth' => $faker->state,
      'dateofbirth' => $faker->date($format = 'Y-m-d', $max = 'now'),
      'levelofentry' => $faker->numberBetween($min = 1, $max=7),
      'gender' => $faker->randomElement(['Male', 'Female']),
      'maritalstatus' => $faker->randomElement(['Single', 'Married']),
      'rrrnumber' => $faker->numberBetween($min =30000000000, $max=70000000000),
      'amount' => $faker->numberBetween($min = 30000, $max=40000),

      ];

      $query = \Drupal::database()->insert('_students');
      $query->fields($student);
      $query->execute();

      //print('<pre>' . print_r($student, TRUE) . '</pre>'); exit();


      $_SESSION['student'] = (object) $student;


      \Drupal::messenger()->addMessage($student, TRUE);

    }

    if ($op == 'Generate') {
      $values = [];

      // $random_names = ['Auwal', 'Sani', 'Salisu', 'Rabiu', 'Khamisu', 'Sadisu', 'Sabiu', 'Tasiu'];

      for ($i=1; $i <= 10; $i++) {
        $values[] = [
      'firstname' => $faker->name,
      'middlename' => $faker->name,
      'surname' => $faker->lastName,
      'phonenumber' => $faker->numberBetween($min =2347000000, $max=2347999999),
      'placeofbirth' => $faker->state,
      'dateofbirth' => $faker->date($format = 'Y-m-d', $max = 'now'),
      'levelofentry' => $faker->numberBetween($min =1, $max=7),
      'gender' => $faker->randomElement(['Male', 'Female']),
      'maritalstatus' => $faker->randomElement(['Single', 'Married']),
      'rrrnumber' => $faker->numberBetween($min =30000000000, $max=70000000000),
      'amount' => $faker->numberBetween($min =30000, $max=40000),
        ];
      }

       //print('<pre>' . print_r($values, TRUE) . '</pre>'); exit();

    // $this->database->truncate('_employee')->execute();

    $query = \Drupal::database()->insert('_students')->fields(['firstname', 'middlename', 'surname', 'phonenumber', 'placeofbirth', 'dateofbirth', 'levelofentry', 'gender', 'maritalstatus', 'rrrnumber', 'amount']);
    foreach ($values as $record) {
        $query->values($record);
    }


    $query->execute();

      //print('<pre>' . print_r($student, TRUE) . '</pre>'); exit();


      $_SESSION['student'] = (object) $student;


      \Drupal::messenger()->addMessage($student, TRUE);

    }



  }

}
