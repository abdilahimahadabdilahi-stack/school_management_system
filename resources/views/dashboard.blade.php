<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('School Management Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Banner -->
            <div class="bg-blue-600 rounded-lg shadow-md p-6 text-white">
                <h3 class="text-2xl font-bold">School Management Dashboard</h3>
                <p class="text-blue-100 mt-1">Ku maamul xogta ardayda, macallimiinta, iyo shaqalaha hab toos ah.</p>
            </div>

            <!-- General Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Students -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Students</p>
                        <h4 class="text-3xl font-bold text-gray-800 mt-1">{{ $students_count ?? 0 }}</h4>
                        <a href="{{ route('students.index') }}" class="text-blue-600 hover:underline text-sm font-semibold mt-2 inline-block">View List &rarr;</a>
                    </div>
                    <div class="p-3 bg-blue-500 rounded-full text-white">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    </div>
                </div>

                <!-- Total Teachers -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Teachers</p>
                        <h4 class="text-3xl font-bold text-gray-800 mt-1">{{ $teachers_count ?? 0 }}</h4>
                        <a href="{{ route('teachers.index') }}" class="text-green-600 hover:underline text-sm font-semibold mt-2 inline-block">View List &rarr;</a>
                    </div>
                    <div class="p-3 bg-green-500 rounded-full text-white">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    </div>
                </div>

                <!-- Total Staff -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Staff</p>
                        <h4 class="text-3xl font-bold text-gray-800 mt-1">{{ $staff_count ?? 0 }}</h4>
                        <a href="{{ route('staff.index') }}" class="text-yellow-600 hover:underline text-sm font-semibold mt-2 inline-block">View List &rarr;</a>
                    </div>
                    <div class="p-3 bg-yellow-500 rounded-full text-white">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <h4 class="text-lg font-bold text-gray-700 mb-4">⚡ Quick Actions</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('students.create') }}" class="flex items-center justify-center gap-2 p-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 font-semibold border border-blue-200">
                        + Add New Student
                    </a>
                    <a href="{{ route('teachers.create') }}" class="flex items-center justify-center gap-2 p-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 font-semibold border border-green-200">
                        + Add New Teacher
                    </a>
                    <a href="{{ route('staff.create') }}" class="flex items-center justify-center gap-2 p-3 bg-yellow-50 text-yellow-700 rounded-lg hover:bg-yellow-100 font-semibold border border-yellow-200">
                        + Add New Staff
                    </a>
                    <a href="{{ route('attendance.create') }}" class="flex items-center justify-center gap-2 p-3 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold border border-gray-300">
                        📋 Mark Attendance
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>