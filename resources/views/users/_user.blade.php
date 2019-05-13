<div class="list-group-item">
	<img src="{{ $user->gravatar() }}" class="mr-3" alt="{{ $user->name }}" width="32">
	<a href="{{ route('users.show', $user) }}" target="_blank">{{ $user->name }}</a>
</div>