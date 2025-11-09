<table>
    <thead>
        <tr>
            @foreach(array_keys((array)$data->first()) as $col)
                <th>{{ strtoupper($col) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                @foreach($row->toArray() as $value)
                    <td>{{ $value }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
