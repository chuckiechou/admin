<?php

namespace proton\core;
/**
 * Class Logger
 * @package proton\core
 */
class Logger{

	const FILE_EXT = '.php';

	const FILE_SECURITY = '<?php ($_GET[\'p\'] && md5(\'GameFactory\' . $_GET[\'p\'] . \'\') == \'88eea2881110adf80c8236fc0b6ed1b8\') or die(\'No direct script access.\');';

	/**
	 * @var  string  Directory to place log files in
	 */
	protected $_directory;

	/**
	 * Creates a new file logger. Checks that the directory exists and
	 * is writable.
	 *
	 *     $writer = new Log_File($directory);
	 *
	 * @param   string  log directory
	 * @return  void
	 */
	public function __construct($directory){
		if (!is_dir($directory) || !is_writable($directory)) {
			throw new \Exception("Directory :$directory must be writable");
		}

		// Determine the directory path
		$this->_directory = realpath($directory) . DIRECTORY_SEPARATOR;
	}

	/**
	 * Writes each of the messages into the log file. The log file will be
	 * appended to the `YYYY/MM/DD.log.php` file, where YYYY is the current
	 * year, MM is the current month, and DD is the current day.
	 *
	 *     $writer->write($messages);
	 *
	 * @param   array   messages
	 * @return  void
	 */
	public function write(array $messages){
		// Set the yearly directory name
		$directory = $this->_directory . date('Y');

		if (!is_dir($directory)) {
			// Create the yearly directory
			mkdir($directory, 02777);

			// Set permissions (must be manually set to fix umask issues)
			chmod($directory, 02777);
		}

		// Add the month to the directory
		$directory .= DIRECTORY_SEPARATOR . date('m');

		if (!is_dir($directory)) {
			// Create the monthly directory
			mkdir($directory, 02777);

			// Set permissions (must be manually set to fix umask issues)
			chmod($directory, 02777);
		}

		// Set the name of the log file
		$filename = $directory . DIRECTORY_SEPARATOR . date('d') . self::FILE_EXT;

		if (!file_exists($filename)) {
			// Create the log file
			file_put_contents($filename, self::FILE_SECURITY . ' ?>' . PHP_EOL);

			// Allow anyone to write to log files
			chmod($filename, 0666);
		}

		foreach ($messages as $message) {
			// Write each message into the log file
			file_put_contents($filename, PHP_EOL . $message['time'] . ' --- ' . $message['level'] . ': ' . $message['body'], FILE_APPEND);
		}
	}

	public function __toString(){
		return spl_object_hash($this);
	}
}
