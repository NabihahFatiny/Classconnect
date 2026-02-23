@extends('layouts.app')

@section('title', 'Select Subject')

@section('content')
    <div style="max-width: 600px; margin: 0 auto;">
        <h1 style="color: #333; margin-bottom: 30px; text-align: center;">Select Subject</h1>
        <p style="color: #666; text-align: center; margin-bottom: 40px;">Please select a subject to view and participate in discussions.</p>

        @if($subjects->count() > 0)
            <form action="{{ route('subjects.store') }}" method="POST">
                @csrf
                <div style="display: grid; gap: 16px;">
                    @foreach($subjects as $subject)
                        <label style="display: block; cursor: pointer;">
                            <input 
                                type="radio" 
                                name="subject_id" 
                                value="{{ $subject->id }}" 
                                required
                                style="display: none;"
                                id="subject_{{ $subject->id }}"
                                {{ $selectedSubjectId == $subject->id ? 'checked' : '' }}
                            >
                            <div 
                                style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s; border: 2px solid #e0e0e0;"
                                onmouseover="this.style.borderColor='#795E2E'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)';"
                                onmouseout="if(!document.getElementById('subject_{{ $subject->id }}').checked) { this.style.borderColor='#e0e0e0'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; }"
                                onclick="document.getElementById('subject_{{ $subject->id }}').checked = true; updateSelected()"
                            >
                                <div style="display: flex; align-items: center; gap: 16px;">
                                    <div style="width: 50px; height: 50px; background: #795E2E; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 20px;">
                                        {{ strtoupper(substr($subject->name, 0, 1)) }}
                                    </div>
                                    <div style="flex: 1;">
                                        <h3 style="color: #333; margin: 0 0 4px 0; font-size: 18px;">{{ $subject->name }}</h3>
                                        @if($subject->code)
                                            <p style="color: #999; margin: 0; font-size: 14px;">{{ $subject->code }}</p>
                                        @endif
                                        @if($subject->description)
                                            <p style="color: #666; margin: 8px 0 0 0; font-size: 14px;">{{ $subject->description }}</p>
                                        @endif
                                    </div>
                                    <div style="width: 24px; height: 24px; border: 2px solid #e0e0e0; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.3s;" id="radio_{{ $subject->id }}">
                                        <div style="width: 12px; height: 12px; background: #795E2E; border-radius: 50%; display: none;" id="dot_{{ $subject->id }}"></div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                <button 
                    type="submit"
                    id="submitBtn"
                    style="width: 100%; background: #e0e0e0; color: #999; padding: 14px; border: none; border-radius: 8px; font-weight: 600; cursor: not-allowed; margin-top: 30px; transition: all 0.3s;"
                    disabled
                >
                    Continue to Discussions
                </button>
            </form>
        @else
            <div style="background: white; padding: 40px; border-radius: 12px; text-align: center;">
                <p style="color: #666; font-size: 18px; margin-bottom: 20px;">No subjects available yet.</p>
                <p style="color: #999; font-size: 14px;">Please contact your administrator to add subjects.</p>
            </div>
        @endif
    </div>

    <script>
        function updateSelected() {
            const selected = document.querySelector('input[name="subject_id"]:checked');
            const submitBtn = document.getElementById('submitBtn');
            
            // Reset all radio indicators
            document.querySelectorAll('[id^="radio_"]').forEach(radio => {
                radio.style.borderColor = '#e0e0e0';
                const dot = radio.querySelector('[id^="dot_"]');
                if(dot) dot.style.display = 'none';
            });
            
            if(selected) {
                const radioId = 'radio_' + selected.value;
                const dotId = 'dot_' + selected.value;
                const radio = document.getElementById(radioId);
                const dot = document.getElementById(dotId);
                
                if(radio) {
                    radio.style.borderColor = '#795E2E';
                }
                if(dot) {
                    dot.style.display = 'block';
                }
                
                submitBtn.disabled = false;
                submitBtn.style.background = '#795E2E';
                submitBtn.style.color = 'white';
                submitBtn.style.cursor = 'pointer';
            } else {
                submitBtn.disabled = true;
                submitBtn.style.background = '#e0e0e0';
                submitBtn.style.color = '#999';
                submitBtn.style.cursor = 'not-allowed';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const selected = document.querySelector('input[name="subject_id"]:checked');
            if(selected) {
                updateSelected();
            }
        });
    </script>
@endsection

