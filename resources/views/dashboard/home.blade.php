@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="dashboard-home">
        <div class="welcome-section">
            <h2>Welcome back, {{ Auth::user()->name }}</h2>
            <p>Here's your sales overview</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📦</div>
                <div class="stat-info">
                    <h3>{{ $totalProducts }}</h3>
                    <p>Total Products</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-info">
                    <h3>{{ $totalSalesCompleted }}</h3>
                    <p>Sales Completed</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <div class="stat-info">
                    <h3>{{ $totalPendingTransactions }}</h3>
                    <p>Pending Transactions</p>
                </div>
            </div>
        </div>

        <div class="recent-activity">
            <h3>Recent Activity</h3>
            <div class="activity-list">
                <div class="activity-item">
                    <span class="activity-icon">🛒</span>
                    <div class="activity-details">
                        <p>New order received</p>
                        <span class="activity-time">2 minutes ago</span>
                    </div>
                </div>
                <div class="activity-item">
                    <span class="activity-icon">✅</span>
                    <div class="activity-details">
                        <p>Order #123 completed</p>
                        <span class="activity-time">1 hour ago</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection