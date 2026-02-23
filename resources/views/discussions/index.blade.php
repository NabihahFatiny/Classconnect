@extends('layouts.app')

@section('title', 'Discussions')

@section('content')
    <div style="margin-bottom: 20px;">
        <form action="{{ route('subjects.clear') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" style="background: none; border: none; color: #795E2E; text-decoration: none; cursor: pointer; padding: 0; font-size: inherit;">
                ← Back to Select Subject
            </button>
        </form>
    </div>
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h1 style="color: #333; margin: 0 0 8px 0;">Discussions</h1>
            <div style="display: flex; align-items: center; gap: 12px;">
                <span style="color: #795E2E; font-weight: 600; font-size: 16px;">📚 {{ $subject->name }}</span>
                <a href="{{ route('subjects.select') }}" style="color: #666; font-size: 14px; text-decoration: none;">
                    (Change Subject)
                </a>
            </div>
        </div>
        <a href="{{ route('discussions.create') }}" style="background: #795E2E; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: background 0.3s;">
            + New Discussion
        </a>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search and Filter Section -->
    <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 24px;">
        <form method="GET" action="{{ route('discussions.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
            <!-- Search Input -->
            <div style="flex: 1; min-width: 250px;">
                <label for="search" style="display: block; margin-bottom: 6px; color: #333; font-weight: 600; font-size: 14px;">Search</label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    value="{{ $search ?? '' }}" 
                    placeholder="Search by title or author name..." 
                    style="width: 100%; padding: 10px 14px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; transition: border-color 0.3s; height: 42px; box-sizing: border-box;"
                    onfocus="this.style.borderColor='#795E2E'"
                    onblur="this.style.borderColor='#e0e0e0'"
                >
            </div>

            <!-- Class Filter Dropdown -->
            <div style="min-width: 180px;">
                <label for="class" style="display: block; margin-bottom: 6px; color: #333; font-weight: 600; font-size: 14px;">Filter by Class</label>
                <select 
                    id="class" 
                    name="class" 
                    style="width: 100%; padding: 10px 14px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; transition: border-color 0.3s; height: 42px; box-sizing: border-box;"
                    onfocus="this.style.borderColor='#795E2E'"
                    onblur="this.style.borderColor='#e0e0e0'"
                >
                    <option value="">All Classes</option>
                    @foreach($availableClasses ?? [] as $class)
                        <option value="{{ $class }}" {{ ($classFilter ?? '') === $class ? 'selected' : '' }}>
                            {{ $class }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Button -->
            <div>
                <button 
                    type="submit" 
                    style="background: #795E2E; color: white; padding: 10px 24px; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.3s; height: 42px;"
                    onmouseover="this.style.background='#6a5127'; this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.background='#795E2E'; this.style.transform='translateY(0)'"
                >
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 6px;">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    Search
                </button>
            </div>

            <!-- Clear Button (only show if filters are active) -->
            @if(($search ?? '') || ($classFilter ?? ''))
                <div>
                    <a 
                        href="{{ route('discussions.index') }}" 
                        style="background: #6c757d; color: white; padding: 10px 24px; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; text-decoration: none; display: inline-block; height: 42px; line-height: 22px; transition: all 0.3s;"
                        onmouseover="this.style.background='#5a6268'; this.style.transform='translateY(-1px)'"
                        onmouseout="this.style.background='#6c757d'; this.style.transform='translateY(0)'"
                    >
                        Clear
                    </a>
                </div>
            @endif
        </form>
    </div>

    @if($discussions->count() > 0)
        <div style="display: flex; flex-direction: column; gap: 20px;">
            @foreach($discussions as $discussion)
                <div style="background: white; padding: 28px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s ease; border: 1px solid #f0f0f0;" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.12)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)'">
                    <div style="display: flex; justify-content: space-between; align-items: start; gap: 20px;">
                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                <h2 style="color: #2c3e50; margin: 0; font-size: 22px; font-weight: 600; line-height: 1.3;">
                                    <a href="{{ route('discussions.show', $discussion) }}" style="color: #795E2E; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#5a4723'" onmouseout="this.style.color='#795E2E'">
                                        {{ $discussion->title }}
                                    </a>
                                </h2>
                                @if($discussion->class)
                                    <span style="background: {{ ($isLecturer ?? false) || ($discussion->class === $userClass) ? '#795E2E' : '#999' }}; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                        {{ $discussion->class }}
                                    </span>
                                @endif
                                @if($discussion->class && !($isLecturer ?? false) && $discussion->class !== $userClass)
                                    <span style="background: #fff3cd; color: #856404; padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: 500; border: 1px solid #ffc107;">
                                        View Only
                                    </span>
                                @endif
                            </div>
                            <p style="color: #555; margin: 0 0 16px 0; line-height: 1.7; font-size: 15px;">
                                {{ Str::limit($discussion->content, 150) }}
                            </p>
                            @if($discussion->image)
                                <div style="margin: 16px 0;">
                                    <img src="{{ asset('storage/' . $discussion->image) }}" alt="Discussion Image" style="max-width: 200px; max-height: 150px; border-radius: 8px; object-fit: cover; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                </div>
                            @endif
                            <div style="display: flex; flex-wrap: wrap; gap: 16px; color: #7a7a7a; font-size: 13px; margin-bottom: 16px; padding-top: 12px; border-top: 1px solid #f0f0f0;">
                                <span style="display: flex; align-items: center; gap: 6px;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <span style="font-weight: 500;">{{ $discussion->user->name ?? 'Anonymous' }}</span>
                                </span>
                                <span style="display: flex; align-items: center; gap: 6px;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                    <span>{{ $discussion->comments->count() }} {{ Str::plural('comment', $discussion->comments->count()) }}</span>
                                </span>
                                <span style="display: flex; align-items: center; gap: 6px;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <span>{{ $discussion->created_at->diffForHumans() }}</span>
                                </span>
                            </div>
                            @php
                                $currentUserId = auth()->id() ?? \App\Models\User::first()->id ?? 1;
                                // Lecturers can always interact, students only with their class
                                $canInteract = ($isLecturer ?? false) || ($userClass && $discussion->class === $userClass) || ! $discussion->class;
                            @endphp
                            @if($discussion->user_id == $currentUserId && $canInteract)
                                <div style="display: flex; gap: 10px; margin-top: 16px; padding-top: 16px; border-top: 1px solid #f0f0f0;">
                                    <a href="{{ route('discussions.edit', $discussion) }}" style="background: #795E2E; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px;" onmouseover="this.style.background='#6a5127'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#795E2E'; this.style.transform='translateY(0)'">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('discussions.destroy', $discussion) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this discussion? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px;" onmouseover="this.style.background='#c82333'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#dc3545'; this.style.transform='translateY(0)'">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 30px;">
            {{ $discussions->links() }}
        </div>
    @else
        <div style="background: white; padding: 40px; border-radius: 12px; text-align: center;">
            <p style="color: #666; font-size: 18px; margin-bottom: 20px;">No discussions yet. Be the first to start one!</p>
            <a href="{{ route('discussions.create') }}" style="background: #795E2E; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                Create First Discussion
            </a>
        </div>
    @endif
@endsection
