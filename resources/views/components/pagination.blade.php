<p>
    {{ trans('messages.pagination', [
          'start' => ($collection->currentPage() - 1) * $collection->perPage() + 1,
          'end' => $collection->currentPage() * min($collection->perPage(), $collection->total()),
          'total' => $collection->total()
        ])
    }}
</p>

{{ $collection->appends(request()->query())->links() }}
