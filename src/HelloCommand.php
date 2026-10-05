<?php

namespace App;

use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:hello')]
final class HelloCommand
{
    public function __invoke(
        SymfonyStyle $io,
        #[Argument] string $name = 'World',
        #[Option] bool $formal = false
    ): int {
        $io->title(sprintf('%s %s', $formal ? 'Hello' : 'Hey', $name));

        return Command::SUCCESS;
    }
}
