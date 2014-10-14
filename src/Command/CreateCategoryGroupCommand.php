<?php

namespace eecli\Command;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class CreateCategoryGroupCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'create:category_group';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Create a category group.';

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return array(
            array(
                'name', // name
                InputArgument::REQUIRED, // mode
                'The name of the category group.', // description
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function fire()
    {
        $this->getApplication()->newInstance('\\eecli\\CodeIgniter\\Controller\\AdminContentController');

        $name = $this->argument('name');

        $_POST = array(
            'group_name' => $name,
            'field_html_formatting' => 'all',
            'exclude_group' => '0',
        );

        ee()->update_category_group();

        if (ee()->output->getErrorMessage()) {
            $this->error(ee()->output->getErrorMessage());

            return;
        }

        $query = ee()->db->select('group_id')
            ->where('group_name', $name)
            ->where('site_id', ee()->config->item('site_id'))
            ->get('category_groups');

        $this->info(sprintf('Category group %s (%s) created.', $name, $query->row('group_id')));

        $query->free_result();
    }
}
