<?php

namespace App\Command;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\Migrations\Configuration\EntityManager\ManagerRegistryEntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'post:update-state')]
class UpdatePostStateCommand extends Command
{
    protected static $defaultDescription = 'Update a post state.';
    private $entityManager;
    private $postRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->entityManager = $doctrine->getManager();
        $this->postRepository = $this->entityManager->getRepository(Post::class);
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Post Updated',
            '============',
            '',
        ]);
        // retrieve the argument value using getArgument()
        $output->writeln('Post Id: '.$input->getArgument('id'));

        $post = $this->postRepository->find($input->getArgument('id'));
        $state = $post->getState();
        $state = $state == Post::SHOW ? Post::HIDE : Post::SHOW;
        $post->setState($state);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'The post id of the user.')
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you to update a post state...')
        ;
    }
}