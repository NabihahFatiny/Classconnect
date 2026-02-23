@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 style="color: #333; margin-bottom: 20px;">Welcome to ClassConnect</h1>
    <p style="color: #666; margin-bottom: 30px;">High School Learning Platform</p>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <a href="{{ route('lessons.index') }}" style="text-decoration: none; color: inherit;">
            <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'" onmouseout="this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                <h3 style="color: #667eea; margin-bottom: 10px;">📚 Lessons</h3>
                <p style="color: #666;">Manage your course lessons</p>
            </div>
        </a>
        <a href="{{ route('assignments.index') }}" style="text-decoration: none; color: inherit;">
            <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'" onmouseout="this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                <h3 style="color: #667eea; margin-bottom: 10px;">📝 Assignments</h3>
                <p style="color: #666;">Create and track assignments</p>
            </div>
        </a>
        <a href="{{ route('discussions.index') }}" style="text-decoration: none; color: inherit;">
            <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'" onmouseout="this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                <h3 style="color: #667eea; margin-bottom: 10px;">💬 Discussions</h3>
                <p style="color: #666;">Engage with students</p>
            </div>
        </a>
    </div>
@endsection

