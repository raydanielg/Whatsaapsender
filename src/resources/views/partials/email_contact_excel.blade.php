<tr>
    <td>name</td>
    <td>email</td>
</tr>
@forelse($contacts as $contact)
    @if($contact->name!='')
    <tr>
        <td>{{$contact->name}}</td>
        <td>{{$contact->email}}</td>
    </tr>
    @endif
@endforeach