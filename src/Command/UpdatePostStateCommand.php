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
        if (($id = $input->getArgument('id')) !== null) {
            if ($id = intval($id)) {
                $output->writeln('Post Id: ' . $id);

                $post = $this->postRepository->find($id);
                $state = $post->getState();
                $newState = $state == Post::SHOW ? Post::HIDE : Post::SHOW;
                $post->setState($newState);
                $this->entityManager->flush();
                $output->writeln('Post update state with id=' . $id . ' completed');
            } else {
                $output->writeln('Post Id Invalid');
            }
        } else {
            $output->writeln('Post update state all');

            $posts = $this->postRepository->findAll();
            foreach ($posts as $post) {
                $state = $post->getState();
                $newState = $state == Post::SHOW ? Post::HIDE : Post::SHOW;
                $post->setState($newState);
            }
            $this->entityManager->flush();
            $output->writeln('Post update all state completed');
        }

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::OPTIONAL, 'The post id of the user.')
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you to update a post state...')
        ;
    }
}