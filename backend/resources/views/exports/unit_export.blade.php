<table>
    <thead>
        <tr>
            <th style="font-weight:bold;text-align:center;border:1px solid #000;">ID_SATUAN</th>
            <th style="font-weight:bold;text-align:center;border:1px solid #000;">NAMA_SATUAN</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
        <tr>
            <td style="width: 300px;border:1px solid #000;">{{ $row->id }}</td>
            <td style="width: 150px;border:1px solid #000;">{{ $row->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>