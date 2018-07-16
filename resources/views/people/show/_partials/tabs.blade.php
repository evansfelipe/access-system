<div class="col offset-lg-2 col-lg-8">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="{{ route('people.show', ['person' => $person_id, 'tab' => 'personal-information']) }}" class="nav-link {{ $active == 0 ? 'active' : '' }}" href="#">Información personal</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('people.show', ['person' => $person_id, 'tab' => 'working-information']) }}" class="nav-link {{ $active == 1 ? 'active' : '' }}" href="#">Información laboral</a>
        </li>
    </ul>
</div>