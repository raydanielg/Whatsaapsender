 <tr>
    <td>name</td>
    <td>contact_no</td>
</tr>
@forelse($contacts as $contact)
    <tr>
        <td>{{$contact->name}}</td>
        <td>{{$contact->contact_no}}</td>
    </tr>
@endforeach 