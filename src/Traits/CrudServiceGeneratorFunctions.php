<?php

namespace SoftDreams\LaravelVuexCrud\Traits;


trait CrudServiceGeneratorFunctions
{

	/**
	 * The filesystem instance.
	 *
	 * @var Filesystem
	 */
	protected $files;

	/**
	 * @var Composer
	 */
	private $composer;

	/**
	 * The config section that defines paths and namespaces.
	 *
	 * @var string
	 */
	protected $crud_section = 'default';

	/**
	 * The config section that defines paths and namespaces.
	 *
	 * @var string
	 */
	protected $my_class_name = 'example';

	/**
	 * Create a new command instance.
	 *
	 * @param Filesystem $files
	 * @param Composer $composer
	 */
	public function __construct(Filesystem $files)
	{
		parent::__construct();
		$this->files = $files;
		$this->composer = app()['composer'];
	}

	/**
	 * Compile the api stub.
	 *
	 * @return string
	 */
	protected function compileStub($stub_name , $component , $section)
	{
		$stub = $this->files->get(__DIR__ . '/../stubs/' . $stub_name . '.stub');

		$this->replaceClassName($stub)
			->replaceNamespace($stub , $component , $section);
		return $stub;
	}

	/**
	 * Replace the class name in the stub.
	 *
	 * @param  string $stub
	 * @return $this
	 */
	protected function replaceClassName(&$stub)
	{
		$stub = str_replace('{{class}}', $this->my_class_name, $stub);
		return $this;
	}

	/**
	 * Replace the namespace in the stub.
	 *
	 * @param  string $stub
	 * @return $this
	 */
	protected function replaceNamespace(&$stub , $component , $section)
	{
		$section_data = app()['config']["vuexcrud.sections." . $section];
		$namespace = trim($section_data[$component]);
		$stub = str_replace('{{namespace}}', $namespace, $stub);
		return $this;
	}

	/**
	 * Build the directory for the class if necessary.
	 *
	 * @param  string $path
	 * @return string
	 */
	protected function makeDirectory($path)
	{
		if (!$this->files->isDirectory(dirname($path))) {
			$this->files->makeDirectory(dirname($path), 0777, true, true);
		}
	}

	/**
	 * Get the path to where we should store the migration.
	 *
	 * @param  string $name
	 * @return string
	 */
	protected function getPath($component , $section)
	{
		$section_data = app()['config']["vuexcrud.sections." . $section];
		$file_path = '/app/' . trim($section_data[$component] , " /\t\n\r\0\x0B") . '/' . $this->my_class_name . '.php';
		return base_path() . $file_path;
	}
}