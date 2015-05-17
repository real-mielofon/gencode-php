<?php

namespace Api;

use Api\Model\Authenticate;
use Api\Model\Codes;
use Api\Model\Features;
use Api\Model\Users;
use \Exception;
use \Slim\Slim;

// TODO Move all "features" things to a class with index() and get() methods

class Application extends Slim {
	public $configDirectory;
	public $config;

	public function authenticate() {
		return function ($route) {
			if (!isset($_SESSION['user'])) {
				$app = \Slim\Slim::getInstance();
				//$app->redirect('/#/login');

				$res = array(
					'state'   => 'error',
					'message' => 'Need autentification',
				);
				$app->response->headers->set('Content-Type', 'application/json');
				$app->response->setBody(json_encode($res));

				$app->stop();
			};
		};
	}

	protected function initConfig() {
		$config = array();
		if (!file_exists($this->configDirectory) || !is_dir($this->configDirectory)) {
			throw new Exception('Config directory is missing: '.$this->configDirectory, 500);
		}
		foreach (preg_grep('/\\.php$/', scandir($this->configDirectory)) as $filename) {
			$config = array_replace_recursive($config, include $this->configDirectory.'/'.$filename);
		}
		return $config;
	}

	public function __construct(array $userSettings = array(), $configDirectory = 'config') {
		// Slim initialization
		parent::__construct($userSettings);
		$this->config('debug', true);
		//false);
		$this->log->setEnabled(true);
		$this->log->setLevel(\Slim\Log::DEBUG);

		$this->notFound(function () {
				$this->handleNotFound();
			});
		$this->error(function ($e) {
				$this->handleException($e);
			});

		// Config
		$this->configDirectory = __DIR__ .'/../../'.$configDirectory;
		$this->config          = $this->initConfig();

		$this->add(new \Slim\Middleware\SessionCookie(
				array('secret' => 'gencode2015secret')));

		// $this->add(new \Slim\Middleware\SessionCookie(array(
		//     'expires' => '20 minutes',
		//     'path' => '/',
		//     'domain' => null,
		//     'secure' => false,
		//     'httponly' => false,
		//     'name' => 'slim_session',
		//     'secret' => 'CHANGE_ME',
		//     'cipher' => MCRYPT_RIJNDAEL_256,
		//     'cipher_mode' => MCRYPT_MODE_CBC
		// )));

		//$this->add(new Authenticate());

		$this->codes = new Codes($this->config['codes'], $this->config['database']);
		// /features
		$this->get('/features', function () {
				$features = new Features($this->config['features']);
				$this->response->headers->set('Content-Type', 'application/json');
				$this->response->setBody(json_encode($features->getFeatures()));
			});

		$this->get('/features/:id', function ($id) {
				$features = new Features($this->config['features']);
				$feature = $features->getFeature($id);
				if ($feature === null) {
					return $this->notFound();
				}
				$this->response->headers->set('Content-Type', 'application/json');
				$this->response->setBody(json_encode($feature));
			});

		// /login
		$this->post('/login', function () {
				$login = json_decode($this->request->getBody());

				$users = new Users($this->config['users']);
				$user = $users->getUser($login->username);
				if ($user === null) {
					return $this->notFound();
				}
				if (!$users->auth($login->username, $login->password)) {
					return $this->notFound();
				}

				$_SESSION['user'] = $login->username;

				$this->response->headers->set('Content-Type', 'application/json');
				$this->response->setBody(json_encode($user));
			});

		// /logout
		$this->get('/logout', function () {
				unset($_SESSION['user']);
				$res = array(
					'state' => 'success',
				);
				$this->response->headers->set('Content-Type', 'application/json');
				$this->response->setBody(json_encode($res));
			});

		// /available
		$this->get('/available', $this->authenticate(), function () {
				$this->response->headers->set('Content-Type', 'application/json');
				$this->response->setBody(json_encode($this->codes->getAvailable()));
			});

		// /sended
		$this->get('/sended', $this->authenticate(), function () {
				$this->response->headers->set('Content-Type', 'application/json');
				$this->response->setBody(json_encode($this->codes->getSended()));
			});

		// /activated
		$this->get('/activated', $this->authenticate(), function () {
				$this->response->headers->set('Content-Type', 'application/json');
				$this->response->setBody(json_encode($this->codes->getActivated()));
			});

		$this->get('/generatekeys', $this->authenticate(), function () {
				$this->response->headers->set('Content-Type', 'application/json');
				$this->response->setBody(json_encode($this->codes->getGenerateKeys()));
			});

		$this->get('/phpinfo', function () {
				phpinfo();
			});
	}

	public function handleNotFound() {
		throw new Exception(
			'Resource '.$this->request->getResourceUri().' using '
			.$this->request->getMethod().' method does not exist.',
			404
		);
	}

	public function handleException(Exception $e) {
		$status     = $e->getCode();
		$statusText = \Slim\Http\Response::getMessageForCode($status);
		if ($statusText === null) {
			$status     = 500;
			$statusText = 'Internal Server Error';
		}

		$this->response->setStatus($status);
		$this->response->headers->set('Content-Type', 'application/json');
		$this->response->setBody(json_encode(array(
					'status'      => $status,
					'statusText'  => preg_replace('/^[0-9]+ (.*)$/', '$1', $statusText),
					'description' => $e->getMessage(),
				)));
	}

	/**c
	 * @return \Slim\Http\Response
	 */
	public function invoke() {
		foreach ($this->middleware as $middleware) {
			$middleware->call();
		}
		$this->response()->finalize();
		return $this->response();
	}
}
