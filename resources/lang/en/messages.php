<?php

return [

    'document' => [
        'title' => 'Title',
        'title_invalid' => 'Can\'t create document with that name, please try another.',
        'category' => 'Category',
        'group' => 'Group',
        'group_others' => 'and others',
        'activity' => 'Activity',

        'created' => 'Document Created',
        'deleted' => 'Document Deleted',

        'publish_state' => 'Publish State',
        'publish_states' => [
            'all' => 'All',
            \App\Models\Doc::PUBLISH_STATE_PUBLISHED => 'Published',
            \App\Models\Doc::PUBLISH_STATE_UNPUBLISHED => 'Unpublished',
            \App\Models\Doc::PUBLISH_STATE_PRIVATE => 'Private',
            \App\Models\Doc::PUBLISH_STATE_DELETED_ADMIN => 'Deleted (by admin)',
            \App\Models\Doc::PUBLISH_STATE_DELETED_USER => 'Deleted (by user)',
        ],

        'discussion_state' => 'Discussion State',
        'discussion_states' => [
            \App\Models\Doc::DISCUSSION_STATE_OPEN => 'Open',
            \App\Models\Doc::DISCUSSION_STATE_CLOSED => 'Closed',
            \App\Models\Doc::DISCUSSION_STATE_HIDDEN => 'Hidden',
        ],

        'create_as' => 'Sponsor As',

        'list' => 'Documents',
        'create' => 'Create Document',
        'edit' => 'Edit Document',
    ],

    'submit' => 'Submit',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'close' => 'Close',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
    'order_by' => 'Order By',
    'order_by_direction' => 'Order By Direction',
    'order_by_dir_desc' => 'Descending',
    'order_by_dir_asc' => 'Ascending',
    'limit' => 'Limit',
    'pagination' => 'Showing :start to :end of :total entries',
    'actions' => 'Actions',
    'id' => 'Id',

];
