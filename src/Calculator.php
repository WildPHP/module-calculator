<?php
/**
 * Copyright 2017 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Modules\Calculator;


use MathParser\Exceptions\DivisionByZeroException;
use MathParser\Exceptions\MathParserException;
use MathParser\Exceptions\SyntaxErrorException;
use MathParser\Interpreting\Evaluator;
use MathParser\StdMathParser;
use unreal4u\TelegramAPI\Telegram\Methods\SendMessage;
use WildPHP\Core\Channels\Channel;
use WildPHP\Core\Commands\CommandHandler;
use WildPHP\Core\Commands\CommandHelp;
use WildPHP\Core\ComponentContainer;
use WildPHP\Core\Connection\Queue;
use WildPHP\Core\ContainerTrait;
use WildPHP\Core\EventEmitter;
use WildPHP\Core\Modules\BaseModule;
use WildPHP\Core\Users\User;
use WildPHP\Modules\TGRelay\TGCommandHandler;
use WildPHP\Modules\TGRelay\TgLog;

class Calculator extends BaseModule
{
	use ContainerTrait;

	/**
	 * @var StdMathParser
	 */
	protected $mathParser;

	/**
	 * Calculator constructor.
	 *
	 * @param ComponentContainer $container
	 */
	public function __construct(ComponentContainer $container)
	{
		$this->setContainer($container);

		$commandHelp = new CommandHelp();
		$commandHelp->addPage('Calculates the result of a mathematical expression. Usage: calc [expression]');
		CommandHandler::fromContainer($container)
			->registerCommand('calc', [$this, 'calcCommand'], $commandHelp, 1, -1);

		EventEmitter::fromContainer($container)->on('telegram.commands.add', function (TGCommandHandler $commandHandler)
		{
			$commandHandler->registerCommand('calc', [$this, 'calcTGCommand'], null, 1, -1);
			$commandHandler->alias('calc', 'c');
		});

		$this->mathParser = new StdMathParser();
	}

	/**
	 * @param Channel $source
	 * @param User $user
	 * @param array $args
	 * @param ComponentContainer $container
	 */
	public function calcCommand(Channel $source, User $user, array $args, ComponentContainer $container)
	{
		$expression = implode(' ', $args);

		$msg = $this->parseExpression($expression);

		$msg = $user->getNickname() . ' > ' . $msg;

		Queue::fromContainer($container)->privmsg($source->getName(), $msg);
	}

	/**
	 * @param TgLog $telegram
	 * @param mixed $chat_id
	 * @param array $args
	 * @param string $channel
	 * @param string $username
	 */
	public function calcTGCommand(TgLog $telegram, $chat_id, array $args, string $channel, string $username)
	{
		$expression = implode(' ', $args);

		$msg = $this->parseExpression($expression);

		$msg = $username . ' > ' . $msg;

		if (empty($channel))
		{
			$sendMessage = new SendMessage();
			$sendMessage->chat_id = $chat_id;
			$sendMessage->text = $msg;
			$telegram->performApiRequest($sendMessage);
			return;
		}

		Queue::fromContainer($this->getContainer())->privmsg($channel, $msg);
	}

	/**
	 * @param string $expression
	 *
	 * @return string
	 */
	protected function parseExpression(string $expression)
	{
		try
		{
			$ast = $this->mathParser->parse($expression);

			$evaluator = new Evaluator();
			$msg = $ast->accept($evaluator);
		}
		catch (MathParserException $exception)
		{
			$msg = 'Exception occurred: ' . get_class($exception);
		}

		return $msg;
	}
}