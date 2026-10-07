<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart School - Admin Console</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- html2pdf.js local bundle for reliable PDF generation -->
    <script src="/js/html2pdf.bundle.min.js"></script>
    
    <style>
        /* Base Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: #f3f7fa;
            color: #1a1f36;
            min-height: 100vh;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Background blur circles */
        .bg-circle {
            position: fixed;
            border-radius: 50%;
            z-index: -1;
            filter: blur(100px);
            opacity: 0.6;
        }

        .bg-circle-1 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #00b4db, #0077be);
            top: -100px;
            right: -100px;
        }

        .bg-circle-2 {
            width: 350px;
            height: 350px;
            background: linear-gradient(135deg, #9c27b0, #e040fb);
            bottom: -50px;
            left: -100px;
        }

        .hidden {
            display: none !important;
        }

        /* Glassmorphic Container */
        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.08);
        }

        /* 1. Login View */
        #login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 40px;
            text-align: center;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #0077be, #00b4db);
            border-radius: 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 20px;
            box-shadow: 0 8px 16px rgba(0, 119, 190, 0.2);
        }

        .login-title {
            font-size: 24px;
            font-weight: 700;
            color: #1a1f36;
            margin-bottom: 8px;
        }

        .login-subtitle {
            font-size: 14px;
            color: #697386;
            margin-bottom: 30px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #4f5b66;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            color: #1a1f36;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #0077be;
            background: white;
            box-shadow: 0 0 0 4px rgba(0, 119, 190, 0.1);
        }

        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0077be, #00b4db);
            color: white;
            box-shadow: 0 4px 12px rgba(0, 119, 190, 0.15);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 119, 190, 0.25);
        }

        .error-message {
            background-color: rgba(255, 77, 79, 0.1);
            border: 1px solid rgba(255, 77, 79, 0.2);
            color: #ff4d4f;
            padding: 12px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: left;
        }

        /* 2. Portal Container & Sidebar */
        #portal-container {
            display: flex;
            min-height: 100vh;
            animation: fadeIn 0.4s ease-out;
        }

        .sidebar {
            width: 260px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-right: 1px solid rgba(255, 255, 255, 0.3);
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 10;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
            padding-left: 8px;
        }

        .brand-logo {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #0077be, #00b4db);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            font-weight: 800;
        }

        .brand-name {
            font-size: 18px;
            font-weight: 700;
            color: #1a1f36;
        }

        .menu-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 4px;
            overflow-y: auto;
            flex: 1;
            /* Hide scrollbar */
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .menu-list::-webkit-scrollbar {
            display: none;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            border-radius: 12px;
            color: #4f5b66;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .menu-item:hover {
            background: rgba(0, 119, 190, 0.05);
            color: #0077be;
        }

        .menu-item.active {
            background: linear-gradient(135deg, #0077be, #00b4db);
            color: white;
            box-shadow: 0 4px 12px rgba(0, 119, 190, 0.15);
        }

        .menu-item svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }

        .sidebar-footer {
            margin-top: auto;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding-top: 20px;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            padding: 0 8px;
        }

        .profile-avatar {
            width: 36px;
            height: 36px;
            background: #e1f5fe;
            color: #0077be;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        .profile-name {
            font-size: 13px;
            font-weight: 700;
            color: #1a1f36;
            max-width: 140px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .profile-role {
            font-size: 11px;
            color: #697386;
        }

        /* 3. Main Workspace */
        .workspace {
            margin-left: 260px;
            flex: 1;
            padding: 40px;
            width: calc(100% - 260px);
        }

        .header-panel {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a1f36;
        }

        .page-subtitle {
            font-size: 14px;
            color: #697386;
            margin-top: 4px;
        }

        /* 4. Dashboard View elements */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 35px;
        }

        .metric-card {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .metric-icon-box {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-blue { background-color: rgba(0, 119, 190, 0.1); color: #0077be; }
        .bg-purple { background-color: rgba(156, 39, 176, 0.1); color: #9c27b0; }
        .bg-green { background-color: rgba(76, 175, 80, 0.1); color: #4caf50; }

        .metric-value {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .metric-label {
            font-size: 13px;
            color: #697386;
            font-weight: 500;
        }

        .shortcut-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .shortcut-card {
            padding: 24px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
        }

        .shortcut-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
            border-color: rgba(0, 119, 190, 0.15);
        }

        .shortcut-title {
            font-size: 16px;
            font-weight: 700;
            color: #1a1f36;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .shortcut-desc {
            font-size: 13px;
            color: #697386;
            line-height: 1.5;
        }

        /* 5. Directory Tables (Users, Teachers, Attendance) */
        .controls-panel {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .search-wrapper {
            position: relative;
            flex: 1;
            min-width: 250px;
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #697386;
            width: 18px;
            height: 18px;
        }

        .search-input {
            width: 100%;
            padding: 12px 16px 12px 42px;
            border: 1.5px solid rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            color: #1a1f36;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: #0077be;
            background: white;
        }

        .filter-select {
            padding: 12px 20px;
            border: 1.5px solid rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            color: #1a1f36;
            outline: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            border-radius: 16px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .data-table th {
            padding: 16px 24px;
            font-size: 13px;
            font-weight: 700;
            color: #4f5b66;
            text-transform: uppercase;
            border-bottom: 1.5px solid rgba(0, 0, 0, 0.05);
            background: rgba(0, 0, 0, 0.02);
        }

        .data-table td {
            padding: 16px 24px;
            font-size: 14px;
            color: #1a1f36;
            border-bottom: 1px solid rgba(0, 0, 0, 0.03);
            background-color: rgba(255, 255, 255, 0.3);
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .data-table tr:hover td {
            background-color: rgba(255, 255, 255, 0.6);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-admin { background-color: rgba(0, 119, 190, 0.1); color: #0077be; }
        .badge-teacher { background-color: rgba(156, 39, 176, 0.1); color: #9c27b0; }
        .badge-student { background-color: rgba(76, 175, 80, 0.1); color: #4caf50; }
        .badge-parent { background-color: rgba(255, 152, 0, 0.1); color: #ff9800; }
        .badge-completed { background-color: rgba(76, 175, 80, 0.1); color: #4caf50; }
        .badge-pending { background-color: rgba(244, 67, 54, 0.1); color: #f44336; }

        .badge-in { background-color: rgba(76, 175, 80, 0.1); color: #4caf50; }
        .badge-out { background-color: rgba(255, 152, 0, 0.1); color: #ff9800; }

        /* Timetable form card */
        .timetable-config-card {
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
        }

        .timetable-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
            margin-top: 25px;
            margin-bottom: 25px;
        }

        .timetable-row {
            display: grid;
            grid-template-columns: 180px 1fr;
            align-items: center;
            gap: 15px;
            padding: 10px 15px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .slot-time {
            font-size: 13px;
            font-weight: 700;
            color: #4f5b66;
        }

        .slot-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 8px;
            background: white;
            font-size: 13px;
            outline: none;
            transition: all 0.2s ease;
        }

        .slot-input:focus {
            border-color: #0077be;
            box-shadow: 0 0 0 3px rgba(0, 119, 190, 0.08);
        }

        .config-selectors {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .select-wrapper {
            flex: 1;
        }

        /* Toast notifications */
        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #1a1f36;
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            z-index: 100;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast-success { border-left: 4px solid #4caf50; }
        .toast-error { border-left: 4px solid #ff4d4f; }

        /* 6. Messaging Hub Layout styles */
        .chat-container {
            display: flex;
            height: calc(100vh - 220px);
            min-height: 500px;
            overflow: hidden;
            border-radius: 20px;
            margin-top: 10px;
        }

        .chat-sidebar {
            width: 320px;
            border-right: 1px solid rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.25);
            flex-shrink: 0;
        }

        .chat-sidebar-tabs {
            display: flex;
            border-bottom: 1.5px solid rgba(0, 0, 0, 0.05);
            background: rgba(255, 255, 255, 0.4);
        }

        .chat-tab-btn {
            flex: 1;
            padding: 14px 10px;
            border: none;
            background: transparent;
            font-size: 13px;
            font-weight: 600;
            color: #697386;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
        }

        .chat-tab-btn.active {
            color: #0077be;
            border-bottom: 3px solid #0077be;
            background: rgba(255, 255, 255, 0.6);
        }

        .chat-sidebar-search {
            padding: 12px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            background: rgba(255, 255, 255, 0.1);
        }

        .chat-sidebar-list {
            flex: 1;
            overflow-y: auto;
            padding: 8px;
        }

        .chat-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 6px;
        }

        .chat-item:hover {
            background: rgba(255, 255, 255, 0.6);
        }

        .chat-item.active {
            background: rgba(0, 119, 190, 0.08);
            border-left: 4px solid #0077be;
        }

        .chat-item-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0077be, #00b4db);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 15px;
            flex-shrink: 0;
            box-shadow: 0 4px 8px rgba(0, 119, 190, 0.15);
        }

        .chat-item-details {
            flex: 1;
            min-width: 0;
        }

        .chat-item-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 4px;
        }

        .chat-item-name {
            font-size: 14px;
            font-weight: 600;
            color: #1a1f36;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chat-item-time {
            font-size: 10px;
            color: #8792a2;
        }

        .chat-item-preview {
            font-size: 12px;
            color: #697386;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chat-item-badge-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 4px;
        }

        .chat-unread-count {
            background: #ff4d4f;
            color: white;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(255, 77, 79, 0.2);
        }

        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(10px);
        }

        .empty-chat-state {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #697386;
            padding: 40px;
            text-align: center;
        }

        .empty-chat-state svg {
            width: 64px;
            height: 64px;
            fill: #0077be;
            opacity: 0.25;
            margin-bottom: 16px;
        }

        .empty-chat-state h3 {
            font-size: 18px;
            font-weight: 700;
            color: #1a1f36;
            margin-bottom: 8px;
        }

        .chat-header {
            padding: 16px 24px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 15px;
            background: rgba(255, 255, 255, 0.55);
        }

        .chat-user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #9c27b0, #e040fb);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 4px 8px rgba(156, 39, 176, 0.15);
        }

        .chat-user-info h4 {
            font-size: 15px;
            font-weight: 700;
            color: #1a1f36;
            margin-bottom: 2px;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: rgba(243, 247, 250, 0.5);
        }

        .message-bubble {
            max-width: 65%;
            padding: 12px 16px;
            border-radius: 16px;
            font-size: 14px;
            line-height: 1.45;
            position: relative;
            animation: bubbleFadeIn 0.2s ease-out;
        }

        @keyframes bubbleFadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .message-sent {
            align-self: flex-end;
            background: linear-gradient(135deg, #0077be, #00b4db);
            color: white;
            border-bottom-right-radius: 4px;
            box-shadow: 0 4px 12px rgba(0, 119, 190, 0.15);
        }

        .message-received {
            align-self: flex-start;
            background: white;
            color: #1a1f36;
            border-bottom-left-radius: 4px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .message-time {
            font-size: 10px;
            margin-top: 4px;
            text-align: right;
            opacity: 0.7;
        }

        .message-received .message-time {
            color: #697386;
        }

        .message-sent .message-time {
            color: rgba(255, 255, 255, 0.85);
        }

        .chat-input-area {
            padding: 16px 24px;
            border-top: 1px solid rgba(0, 0, 0, 0.08);
            display: flex;
            gap: 12px;
            background: rgba(255, 255, 255, 0.6);
            align-items: center;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            transition: all 0.3s ease;
        }
        
        .modal-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }
        
        .modal-container {
            width: 90%;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 24px;
            animation: modalFadeIn 0.3s ease;
        }
        
        @keyframes modalFadeIn {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        
        .modal-header h3 {
            margin: 0;
            color: #1a1a1a;
            font-size: 18px;
            font-weight: 700;
        }
        
        .modal-close-btn {
            background: transparent;
            border: none;
            font-size: 24px;
            color: #697386;
            cursor: pointer;
            transition: color 0.2s;
        }
        
        .modal-close-btn:hover {
            color: #1a1a1a;
        }
        
        .student-info-row {
            margin-bottom: 20px;
            background: rgba(0, 119, 190, 0.05);
            padding: 12px 16px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
        }
        
        .info-label {
            font-weight: 500;
            color: #697386;
        }
        
        .info-value {
            font-weight: 700;
            color: #0077be;
        }
        
        .fees-list-container {
            max-height: 350px;
            overflow-y: auto;
            padding-right: 4px;
        }
        
        .fee-row-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            margin-bottom: 10px;
            transition: all 0.2s;
        }
        
        .fee-row-item:hover {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }
        
        .fee-info-col {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        
        .fee-row-title {
            font-weight: 600;
            color: #1a1a1a;
            font-size: 14px;
        }
        
        .fee-row-meta {
            font-size: 11px;
            color: #697386;
        }
        
        .fee-action-col {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .btn-toggle-fee {
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-toggle-fee.pay {
            background: #4caf50;
            color: white;
        }
        
        .btn-toggle-fee.pay:hover {
            background: #43a047;
        }
        
        .btn-toggle-fee.unpay {
            background: #ff4d4f;
            color: white;
        }
        
        .btn-toggle-fee.unpay:hover {
            background: #e03a3e;
        }

        /* Day selector pills */
        .pill {
            padding: 8px 16px;
            border-radius: 20px;
            background: rgba(255,255,255,0.4);
            border: 1px solid rgba(0,0,0,0.08);
            color: #697386;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .pill:hover {
            background: rgba(255,255,255,0.8);
            color: #1a1f36;
        }
        .pill.active {
            background: #0077be;
            color: white;
            border-color: #0077be;
            box-shadow: 0 4px 10px rgba(0, 119, 190, 0.2);
        }

        /* Timeline styles */
        .timeline-container {
            display: flex;
            flex-direction: column;
            gap: 16px;
            position: relative;
            padding-left: 20px;
        }
        .timeline-container::before {
            content: '';
            position: absolute;
            left: 5px;
            top: 10px;
            bottom: 10px;
            width: 2px;
            background: rgba(0, 119, 190, 0.15);
        }
        .timeline-item {
            position: relative;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            padding: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.2s ease;
        }
        .timeline-item:hover {
            transform: translateX(4px);
            background: rgba(255, 255, 255, 0.6);
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -20px;
            top: 22px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #0077be;
            border: 3px solid #f0f4f8;
            box-shadow: 0 0 0 2px rgba(0, 119, 190, 0.2);
        }
    </style>
</head>
<body>

    <!-- Background blurry gradients -->
    <div class="bg-circle bg-circle-1"></div>
    <div class="bg-circle bg-circle-2"></div>

    <!-- 1. LOGIN CONTAINER -->
    <div id="login-container">
        <div class="login-card glass-card">
            <div class="login-logo">S</div>
            <h1 class="login-title">Smart School</h1>
            <p class="login-subtitle">System Administration Panel</p>
            
            <div id="login-error" class="error-message hidden"></div>
            
            <form id="login-form" action="javascript:void(0);" onsubmit="handleLoginSubmit(event); return false;">
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" id="login-email" class="form-input" placeholder="admin@gmail.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" id="login-password" class="form-input" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 10px;">SIGN IN</button>
            </form>
        </div>
    </div>

    <!-- 2. PORTAL CONTAINER -->
    <div id="portal-container" class="hidden">
        
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="brand-logo">S</div>
                <span class="brand-name">Smart School</span>
            </div>
            
            <ul class="menu-list">
                <li data-roles="admin,teacher">
                    <a class="menu-item active" data-view="dashboard">
                        <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                        Dashboard
                    </a>
                </li>
                <li data-roles="admin">
                    <a class="menu-item" data-view="users">
                        <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        Users Directory
                    </a>
                </li>
                <li data-roles="admin">
                    <a class="menu-item" data-view="grades">
                        <svg viewBox="0 0 24 24" style="fill: currentColor;"><path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4zm12 16H6v-6h12v6zm0-8h-5V4h5v8z"/></svg>
                        Manage Grades
                    </a>
                </li>
                <li data-roles="admin">
                    <a class="menu-item" data-view="subjects-management">
                        <svg viewBox="0 0 24 24" style="fill: currentColor;"><path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H9V9h10v2zm-4 4H9v-2h6v2zm4-8H9V5h10v2z"/></svg>
                        Manage Subjects
                    </a>
                </li>
                <li data-roles="admin,teacher">
                    <a class="menu-item" data-view="attendance">
                        <svg viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                        Attendance
                    </a>
                </li>
                <li data-roles="admin">
                    <a class="menu-item" data-view="timetable">
                        <svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/></svg>
                        Manage Timetable
                    </a>
                </li>
                <li data-roles="teacher">
                    <a class="menu-item" data-view="teacher-timetable">
                        <svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/></svg>
                        My Timetable
                    </a>
                </li>
                <li data-roles="admin,teacher">
                    <a class="menu-item" data-view="meal-plan">
                        <svg viewBox="0 0 24 24"><path d="M8.1 13.34l2.83-2.83L3.91 3.5c-1.56 1.56-1.56 4.09 0 5.66l4.19 4.18zm6.78-1.81c1.53.71 3.68.21 5.27-1.38 1.91-1.91 2.28-4.65.81-6.12-1.46-1.46-4.2-1.1-6.12.81-1.59 1.59-2.09 3.74-1.38 5.27L3.7 19.87l1.41 1.41L12 14.41l6.88 6.88 1.41-1.41L13.41 13l1.47-1.47z"/></svg>
                        Meal Plan
                    </a>
                </li>
                <li data-roles="teacher">
                    <a class="menu-item" data-view="teacher-materials">
                        <svg viewBox="0 0 24 24"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"/></svg>
                        Upload Materials
                    </a>
                </li>
                <li data-roles="teacher">
                    <a class="menu-item" data-view="teacher-uploaded-materials">
                        <svg viewBox="0 0 24 24"><path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H9V9h10v2zm-4 4H9v-2h6v2zm4-8H9V5h10v2z"/></svg>
                        Uploaded Materials
                    </a>
                </li>
                <li data-roles="teacher">
                    <a class="menu-item" data-view="teacher-submissions">
                        <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                        Student Assignment Submissions
                    </a>
                </li>
                <li data-roles="admin,teacher">
                    <a class="menu-item" data-view="exam-results">
                        <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                        Exam Results
                    </a>
                </li>
                <li data-roles="teacher">
                    <a class="menu-item" data-view="teacher-class-results">
                        <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                        My Class Results
                    </a>
                </li>
                <li data-roles="admin">
                    <a class="menu-item" data-view="class-rankings">
                        <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                        Class Rankings
                    </a>
                </li>
                <li data-roles="admin,teacher">
                    <a class="menu-item" data-view="ai-performance">
                        <svg viewBox="0 0 24 24"><path d="M19 9l1.25-2.75L23 5l-2.75-1.25L19 1l-1.25 2.75L15 5l2.75 1.25L19 9zm-7.5.5L9 4 6.5 9.5 1 12l5.5 2.5L9 20l2.5-5.5L17 12l-5.5-2.5zM19 15l-1.25 2.75L15 19l2.75 1.25L19 23l1.25-2.75L23 19l-2.75-1.25L19 15z"/></svg>
                        AI Performance
                    </a>
                </li>
                <li data-roles="admin,teacher">
                    <a class="menu-item" data-view="messages">
                        <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/></svg>
                        Messages
                    </a>
                </li>
                <li data-roles="admin">
                    <a class="menu-item" data-view="fees">
                        <svg viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                        Fees &amp; Payments
                    </a>
                </li>

                <li data-roles="teacher">
                    <a class="menu-item" data-view="teacher-salaries">
                        <svg viewBox="0 0 24 24"><path d="M21 18v1c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2V5c0-1.1.89-2 2-2h14c1.1 0 2 .9 2 2v1h-9c-1.11 0-2 .9-2 2v8c0 1.1.89 2 2 2h9zm-9-2h10V8H12v8zm4-2.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>
                        My Salaries
                    </a>
                </li>
                <li data-roles="admin,teacher">
                    <a class="menu-item" data-view="emergency" style="background: rgba(255, 77, 79, 0.08); border-radius: 12px; margin-bottom: 4px;">
                        <svg viewBox="0 0 24 24" style="fill: #ff4d4f;"><path d="M12 2L1 21h22L12 2zm1 14h-2v-2h2v2zm0-4h-2V10h2v2z"/></svg>
                        <span style="color: #ff4d4f; font-weight: 700;">Emergency Alert 🚨</span>
                    </a>
                </li>
                <li data-roles="admin,teacher">
                    <a class="menu-item" data-view="notices">
                        <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
                        Notices
                    </a>
                </li>
                <li data-roles="admin,teacher">
                    <a class="menu-item" data-view="events">
                        <svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/></svg>
                        Events
                    </a>
                </li>
                <li data-roles="admin">
                    <a class="menu-item" data-view="gallery">
                        <svg viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                        Event Gallery
                    </a>
                </li>
                <li data-roles="admin,teacher">
                    <a class="menu-item" data-view="complaints">
                        <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                        Complaints
                    </a>
                </li>
                <li data-roles="admin,teacher">
                    <a class="menu-item" data-view="leaves">
                        <svg viewBox="0 0 24 24" style="fill: currentColor;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                        Leave Requests
                    </a>
                </li>
                <li data-roles="admin">
                    <a class="menu-item" data-view="admin-content">
                        <svg viewBox="0 0 24 24" style="fill: currentColor;"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                        Teacher Content
                    </a>
                </li>
                <li data-roles="teacher">
                    <a class="menu-item" data-view="teacher-qr">
                        <svg viewBox="0 0 24 24" style="fill: currentColor;"><path d="M4 4h6v6H4V4zm2 2v2h2V6H6zm8-2h6v6h-6V4zm2 2v2h2V6h-2zM4 14h6v6H4v-6zm2 2v2h2v-2H6zm10 2h2v2h-2v-2zm2-2h2v2h-2v-2zm-2-2h2v2h-2v-2zm0-2h2v2h-2V10zm2 2h2v2h-2v-2zM14 14h2v2h-2v-2zm2 2h2v2h-2v-2zm-2 2h2v2h-2v-2zm2-6h2v2h-2v-2zm-4 0h2v2h-2v-2zm2-2h2v2h-2V8zm-2 2h2v2h-2v-2z"/></svg>
                        My Attendance QR
                    </a>
                </li>
                <li data-roles="teacher">
                    <a class="menu-item" data-view="teacher-own-attendance">
                        <svg viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                        My Attendance Logs
                    </a>
                </li>
            </ul>
            
            <div class="sidebar-footer">
                <div class="admin-profile">
                    <div class="profile-avatar" id="admin-avatar">A</div>
                    <div>
                        <div class="profile-name" id="admin-name">System Administrator</div>
                        <div class="profile-role" id="admin-role">Super Admin</div>
                    </div>
                </div>
                <a class="menu-item" id="btn-logout" style="color: #ff4d4f; background: transparent;">
                    <svg viewBox="0 0 24 24" style="fill: #ff4d4f;"><path d="M10.09 15.59L11.5 17l5-5-5-5-1.41 1.41L12.67 11H3v2h9.67l-2.58 2.59zM19 3H5c-1.11 0-2 .9-2 2v4h2V5h14v14H5v-4H3v4c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
                    Sign Out
                </a>
            </div>
        </aside>
        
        <!-- Main Panel Workspace -->
        <main class="workspace">
            
            <!-- HEADER -->
            <div class="header-panel">
                <h2 class="page-title" id="workspace-title">Dashboard</h2>
                <p class="page-subtitle" id="workspace-subtitle">School overview and metrics</p>
            </div>
            
            <!-- VIEW 1: DASHBOARD -->
            <div id="view-dashboard" class="view-panel">
                <div class="metrics-grid">
                    <div class="metric-card glass-card">
                        <div class="metric-icon-box bg-blue">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        </div>
                        <div>
                            <div class="metric-value" id="val-users">0</div>
                            <div class="metric-label">Total Users</div>
                        </div>
                    </div>
                    <div class="metric-card glass-card">
                        <div class="metric-icon-box bg-purple">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
                        </div>
                        <div>
                            <div class="metric-value" id="val-teachers">0</div>
                            <div class="metric-label">Academic Staff</div>
                        </div>
                    </div>
                    <div class="metric-card glass-card">
                        <div class="metric-icon-box bg-green">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                        </div>
                        <div>
                            <div class="metric-value" id="val-students">0</div>
                            <div class="metric-label">Total Students</div>
                        </div>
                    </div>
                </div>

                <div class="shortcut-grid">
                    <div class="shortcut-card glass-card" onclick="switchView('users')">
                        <h3 class="shortcut-title">
                            <span style="color: #0077be;">✦</span> Inspect Users Directory
                        </h3>
                        <p class="shortcut-desc">Manage system accounts, update roles, details, and search across students, teachers, and administrators.</p>
                    </div>
                    <div class="shortcut-card glass-card" onclick="switchView('teachers')">
                        <h3 class="shortcut-title">
                            <span style="color: #9c27b0;">✦</span> View Teacher Specialties
                        </h3>
                        <p class="shortcut-desc">Check teacher qualifications, subjects taught, specialization fields, and school-assigned employee IDs.</p>
                    </div>
                    <div class="shortcut-card glass-card" onclick="switchView('timetable')">
                        <h3 class="shortcut-title">
                            <span style="color: #ff9800;">✦</span> Manage Class Timetables
                        </h3>
                        <p class="shortcut-desc">Add and modify 30-minute time slot entries for any weekday and grade level, updating schedules instantly.</p>
                    </div>
                </div>
            </div>
            
            <!-- VIEW 2: USERS DIRECTORY -->
            <div id="view-users" class="view-panel hidden">
                <div class="view-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <h2></h2>
                    <div style="display:flex; gap:10px; flex-wrap:wrap;">
                        <button onclick="openCreateUserModal('student')" style="width:auto; padding:10px 16px; font-weight:600; font-size:13px; background:#4CAF50; border:none; border-radius:8px; color:white; cursor:pointer; display:flex; align-items:center; gap:6px;">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                            Add Student
                        </button>
                        <button onclick="openCreateUserModal('teacher')" style="width:auto; padding:10px 16px; font-weight:600; font-size:13px; background:#9c27b0; border:none; border-radius:8px; color:white; cursor:pointer; display:flex; align-items:center; gap:6px;">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                            Add Teacher
                        </button>
                        <button onclick="exportUsersCSV()" style="width:auto; padding:10px 15px; font-weight:600; font-size:13px; background:#26a69a; border:none; border-radius:8px; color:white; cursor:pointer;">
                            Export CSV
                        </button>
                        <button onclick="printUsersPDF()" style="width:auto; padding:10px 15px; font-weight:600; font-size:13px; background:#0077be; border:none; border-radius:8px; color:white; cursor:pointer;">
                            Print / PDF Report
                        </button>
                    </div>
                </div>
                <div class="controls-panel">
                    <div class="search-wrapper">
                        <svg class="search-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                        <input type="text" id="users-search" class="search-input" placeholder="Search users by name, email or phone...">
                    </div>
                    <select id="users-filter" class="filter-select">
                        <option value="all">All Roles</option>
                        <option value="student">Students</option>
                        <option value="teacher">Teachers</option>
                        <option value="admin">Administrators</option>
                    </select>
                </div>
                
                <div class="glass-card" style="padding: 10px; overflow: hidden;">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width:50px;">#ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="users-table-body">
                                <tr>
                                    <td colspan="7" style="text-align: center; color: #697386; padding: 30px;">Loading users list...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- VIEW 3: TEACHERS DIRECTORY -->
            <div id="view-teachers" class="view-panel hidden">
                <div class="view-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <h2>Academic Staff</h2>
                    <div style="display:flex; gap:10px;">
                        <button class="btn-toggle-fee pay" onclick="exportTeachersCSV()" style="width: auto; padding: 10px 15px; font-weight: 600; font-size: 13px; background: #4CAF50; border: none; border-radius: 8px; color:white; cursor:pointer;">
                            Export CSV
                        </button>
                        <button class="btn-toggle-fee pay" onclick="printTeachersPDF()" style="width: auto; padding: 10px 15px; font-weight: 600; font-size: 13px; background: #0077be; border: none; border-radius: 8px; color:white; cursor:pointer;">
                            Print / PDF Report
                        </button>
                    </div>
                </div>
                <div class="controls-panel">
                    <div class="search-wrapper">
                        <svg class="search-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                        <input type="text" id="teachers-search" class="search-input" placeholder="Search teachers by name or specialization...">
                    </div>
                </div>
                
                <div class="glass-card" style="padding: 10px; overflow: hidden;">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Qualification</th>
                                    <th>Subject Specialization</th>
                                    <th>Monthly Salary</th>
                                </tr>
                            </thead>
                            <tbody id="teachers-table-body">
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #697386; padding: 30px;">Loading academic staff...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- VIEW: GRADES MANAGEMENT -->
            <div id="view-grades" class="view-panel hidden">
                <div class="view-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <h2></h2>
                </div>
                <div class="controls-panel">
                    <div class="search-wrapper" style="flex:1;">
                        <svg class="search-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                        <input type="text" id="grades-search" class="search-input" placeholder="Search by grade or teacher name..." oninput="renderGradesTable()">
                    </div>
                </div>
                
                <div class="glass-card" style="padding: 10px; overflow: hidden;">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Grade Name</th>
                                    <th>Class Teacher</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="grades-table-body">
                                <tr>
                                <td colspan="3" style="text-align: center; color: #697386; padding: 30px;">Loading grades list...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- VIEW: SUBJECTS MANAGEMENT -->
            <div id="view-subjects-management" class="view-panel hidden">
                <div class="glass-card" style="padding: 20px; max-width: 600px; margin: 0 auto 20px auto; background: white;">
                    <form id="add-subject-form" onsubmit="submitAddSubjectToGrade(event)">
                        <div style="margin-bottom: 16px;">
                            <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Select Grade</label>
                            <select id="add-subject-grade-select" class="search-input" required style="border:1px solid rgba(0,0,0,0.15); background:white; height: 42px; padding: 0 10px;">
                                <option value="">Loading grades...</option>
                            </select>
                        </div>
                        
                        <div style="margin-bottom: 20px;">
                            <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Subject Name</label>
                            <input type="text" id="add-subject-name" class="search-input" required placeholder="e.g. Science, Maths, History" style="border:1px solid rgba(0,0,0,0.15);">
                        </div>
                        
                        <div style="display:flex; justify-content:flex-end;">
                            <button type="submit" class="btn-toggle-fee pay" 
                                style="padding:12px 20px; font-size:14px; width:100%; background:#9c27b0; border:none; color:white; cursor:pointer; font-weight: bold; border-radius: 8px;">
                                Create Subject for Grade
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="glass-card" style="padding: 20px; max-width: 800px; margin: 0 auto; background: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h3 style="margin: 0; color: #1a1f36; font-size: 16px;">Subject History by Grade</h3>
                        <div class="search-wrapper" style="width: 250px; margin: 0;">
                            <svg class="search-icon" fill="currentColor" viewBox="0 0 24 24" style="left: 10px; width: 18px; height: 18px;"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                            <input type="text" id="subjects-history-search" class="search-input" placeholder="Filter by grade or subject..." style="padding-left: 35px; height: 35px;" oninput="filterSubjectsHistory()">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Target Grade</th>
                                    <th>Subject Name</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="subjects-history-table-body">
                                <tr>
                                    <td colspan="3" style="text-align: center; color: #697386; padding: 30px;">Loading subjects...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- VIEW 4: STUDENT ATTENDANCE LOGS -->
            <!-- VIEW: ATTENDANCE LOGS -->
            <div id="view-attendance" class="view-panel hidden">
                <div class="view-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <h2>Attendance Logs</h2>
                </div>

                <!-- TABS CONTAINER (Only displayed for admin) -->
                <div id="attendance-tabs-container" class="glass-card" style="padding: 10px; margin-bottom: 20px; display: flex; gap: 10px; width: fit-content; background: rgba(255, 255, 255, 0.5);">
                    <button type="button" id="attendance-tab-students" onclick="switchAttendanceTab('students')" style="padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; background: #0077be; color: white; transition: all 0.25s ease;">Student Attendance</button>
                    <button type="button" id="attendance-tab-teachers" onclick="switchAttendanceTab('teachers')" style="padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; background: transparent; color: #697386; transition: all 0.25s ease;">Teacher Attendance</button>
                </div>

                <!-- STUDENT ATTENDANCE PANE -->
                <div id="attendance-students-pane">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; flex-wrap: wrap; gap: 10px;">
                        <div class="search-wrapper" style="width: 100%; max-width: 400px; margin: 0;">
                            <svg class="search-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                            <input type="text" id="attendance-search" class="search-input" placeholder="Search student logs by name, id, date or status...">
                        </div>
                        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                            <!-- Grade Filter Dropdown -->
                            <select id="attendance-grade-filter" onchange="applyAttendanceGradeFilter()" style="padding: 10px 14px; border: 1.5px solid #d1d9e0; border-radius: 8px; font-size: 13px; font-weight: 600; font-family: inherit; background: white; color: #1a1f36; cursor: pointer; outline: none; transition: border-color 0.2s;">
                                <option value="">All Grades</option>
                            </select>
                            <button class="btn-toggle-fee pay" onclick="exportStudentAttendanceCSV()" style="width: auto; padding: 10px 15px; font-weight: 600; font-size: 13px; background: #4CAF50; border: none; border-radius: 8px; color:white; cursor:pointer;">
                                Export CSV
                            </button>
                            <button class="btn-toggle-fee pay" onclick="printStudentAttendancePDF()" style="width: auto; padding: 10px 15px; font-weight: 600; font-size: 13px; background: #0077be; border: none; border-radius: 8px; color:white; cursor:pointer;">
                                Print / PDF Report
                            </button>
                        </div>
                    </div>
                    
                    <div class="glass-card" style="padding: 10px; overflow: hidden;">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>User</th>
                                        <th>Grade</th>
                                        <th>Role</th>
                                        <th>Date</th>
                                        <th>Clock In</th>
                                        <th>Clock Out</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="attendance-table-body">
                                    <tr>
                                        <td colspan="8" style="text-align: center; color: #697386; padding: 30px;">Loading logs...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TEACHER ATTENDANCE PANE -->
                <div id="attendance-teachers-pane" class="hidden">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; flex-wrap: wrap; gap: 10px;">
                        <div class="search-wrapper" style="width: 100%; max-width: 400px; margin: 0;">
                            <svg class="search-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                            <input type="text" id="teacher-attendance-search" class="search-input" placeholder="Search teacher logs by name, id, email, date or status...">
                        </div>
                        <div style="display:flex; gap:10px;">
                            <button class="btn-toggle-fee pay" onclick="exportTeacherAttendanceCSV()" style="width: auto; padding: 10px 15px; font-weight: 600; font-size: 13px; background: #4CAF50; border: none; border-radius: 8px; color:white; cursor:pointer;">
                                Export CSV
                            </button>
                            <button class="btn-toggle-fee pay" onclick="printTeacherAttendancePDF()" style="width: auto; padding: 10px 15px; font-weight: 600; font-size: 13px; background: #0077be; border: none; border-radius: 8px; color:white; cursor:pointer;">
                                Print / PDF Report
                            </button>
                        </div>
                    </div>
                    
                    <div class="glass-card" style="padding: 10px; overflow: hidden;">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Teacher ID</th>
                                        <th>Teacher</th>
                                        <th>Email</th>
                                        <th>Date</th>
                                        <th>Clock In</th>
                                        <th>Clock Out</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="teacher-attendance-table-body">
                                    <tr>
                                        <td colspan="7" style="text-align: center; color: #697386; padding: 30px;">Loading logs...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- VIEW: TEACHER OWN ATTENDANCE LOGS -->
            <div id="view-teacher-own-attendance" class="view-panel hidden">
                <div class="controls-panel">
                    <div class="search-wrapper">
                        <svg class="search-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                        <input type="text" id="teacher-own-attendance-search" class="search-input" placeholder="Search my logs by date or status...">
                    </div>
                </div>
                
                <div class="glass-card" style="padding: 10px; overflow: hidden;">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="teacher-own-attendance-table-body">
                                <tr>
                                    <td colspan="4" style="text-align: center; color: #697386; padding: 30px;">Loading my logs...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- VIEW 5: MANAGE TIMETABLE -->
            <div id="view-timetable" class="view-panel hidden">
                <div class="timetable-tabs" style="display: flex; gap: 10px; margin-bottom: 15px;">
                    <button type="button" class="chat-tab-btn active" id="btn-class-timetable" onclick="switchTimetableTab('class')" style="width: auto; padding: 10px 24px; border-radius: 8px;">Class Timetable</button>
                    <button type="button" class="chat-tab-btn" id="btn-teacher-timetable" onclick="switchTimetableTab('teacher')" style="width: auto; padding: 10px 24px; border-radius: 8px;">Teacher Timetable</button>
                </div>

                <div class="glass-card timetable-config-card">
                    <div class="config-selectors">
                        <div class="select-wrapper">
                            <label class="form-label">Day of Week</label>
                            <select id="timetable-day" class="filter-select" style="width: 100%;">
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                        <div class="select-wrapper" id="timetable-grade-wrapper">
                            <label class="form-label">Grade</label>
                            <select id="timetable-grade" class="filter-select" style="width: 100%;">
                                <option value="Grade 1">Grade 1</option>
                                <option value="Grade 2">Grade 2</option>
                                <option value="Grade 3">Grade 3</option>
                                <option value="Grade 4">Grade 4</option>
                                <option value="Grade 5">Grade 5</option>
                                <option value="Grade 6">Grade 6</option>
                                <option value="Grade 7">Grade 7</option>
                                <option value="Grade 8">Grade 8</option>
                                <option value="Grade 9">Grade 9</option>
                                <option value="Grade 10">Grade 10</option>
                                <option value="Grade 11">Grade 11</option>
                                <option value="Grade 12">Grade 12</option>
                            </select>
                        </div>
                        <div class="select-wrapper hidden" id="timetable-teacher-wrapper">
                            <label class="form-label">Teacher</label>
                            <select id="timetable-teacher" class="filter-select" style="width: 100%;">
                                <!-- Loaded dynamically -->
                            </select>
                        </div>
                    </div>
                    
                    <h3 style="font-size: 16px; font-weight: 700; margin-top: 25px; margin-bottom: 5px;">Time Slots</h3>
                    <p style="font-size: 13px; color: #697386; margin-bottom: 20px;">Provide subject names (e.g. Maths, Science, Interval) for each slot:</p>
                    
                    <form id="timetable-form">
                        <div class="timetable-grid" id="timetable-slots-container">
                            <!-- Slots will be injected dynamically -->
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-top: 15px;">SAVE TIMETABLE</button>
                    </form>
                </div>
            </div>

            <!-- VIEW 6: MESSAGES -->
            <div id="view-messages" class="view-panel hidden">
                <div class="chat-container glass-card">
                    <!-- Left Sidebar: Conversations & Contacts -->
                    <div class="chat-sidebar">
                        <div class="chat-sidebar-tabs">
                            <button class="chat-tab-btn active" id="chat-tab-chats" onclick="switchChatTab('chats')">Recent Chats</button>
                            <button class="chat-tab-btn" id="chat-tab-contacts" onclick="switchChatTab('contacts')">Contacts</button>
                        </div>
                        <div class="chat-sidebar-search">
                            <input type="text" id="chat-search" class="slot-input" placeholder="Search..." oninput="filterChatSidebar()">
                        </div>
                        <div class="chat-sidebar-list" id="chat-sidebar-list">
                            <!-- Populated dynamically -->
                        </div>
                    </div>
                    
                    <!-- Right: Active Chat Area -->
                    <div class="chat-area" id="chat-area-empty">
                        <div class="empty-chat-state">
                            <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/></svg>
                            <h3>Select a conversation</h3>
                            <p>Choose a contact from the list to start messaging.</p>
                        </div>
                    </div>
                    
                    <div class="chat-area hidden" id="chat-area-active">
                        <div class="chat-header">
                            <div class="chat-user-avatar" id="active-chat-avatar">U</div>
                            <div class="chat-user-info">
                                <h4 id="active-chat-name">User Name</h4>
                                <span class="badge" id="active-chat-badge" style="margin-left: 0; margin-top: 4px;">Role</span>
                            </div>
                        </div>
                        <div class="chat-messages" id="chat-messages-box">
                            <!-- Messages will be injected dynamically -->
                        </div>
                        <form class="chat-input-area" id="chat-send-form" onsubmit="sendChatMessage(event)">
                            <input type="text" id="chat-input-text" class="slot-input" placeholder="Type a message..." required autocomplete="off">
                            <button type="submit" class="btn btn-primary" style="width: auto; margin-top: 0; padding: 10px 24px;">Send</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- VIEW: FEES & PAYMENTS -->
            <div id="view-fees" class="view-panel hidden">
                <div class="view-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <h2></h2>
                </div>

                <!-- TABS CONTAINER -->
                <div id="fees-tabs-container" class="glass-card" style="padding: 10px; margin-bottom: 20px; display: flex; gap: 10px; width: fit-content; background: rgba(255, 255, 255, 0.5);">
                    <button type="button" id="fees-tab-students" onclick="switchFeesTab('students')" style="padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; background: #0077be; color: white; transition: all 0.25s ease;">Student Fees</button>
                    <button type="button" id="fees-tab-teachers" onclick="switchFeesTab('teachers')" style="padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; background: transparent; color: #697386; transition: all 0.25s ease;">Teacher Salaries</button>
                </div>

                <!-- PANE 1: Student Fees -->
                <div id="fees-students-pane">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; flex-wrap: wrap; gap: 10px;">
                        <h3 style="font-size:18px; font-weight:600; color:#1a1f36; margin:0;">Student Fees Management</h3>
                        <div style="display:flex; gap:10px;">
                            <button class="btn-toggle-fee pay" onclick="exportFeesCSV()" style="width: auto; padding: 10px 15px; font-weight: 600; font-size: 13px; background: #4CAF50; border: none; border-radius: 8px; color:white; cursor:pointer;">
                                Export CSV
                            </button>
                            <button class="btn-toggle-fee pay" onclick="printFeesPDF()" style="width: auto; padding: 10px 15px; font-weight: 600; font-size: 13px; background: #0077be; border: none; border-radius: 8px; color:white; cursor:pointer;">
                                Print / PDF Report
                            </button>
                        </div>
                    </div>
                    
                    <!-- Filter Controls -->
                    <div class="controls-panel" style="gap: 12px; flex-wrap: wrap; margin-bottom:15px;">
                        <!-- Month Select -->
                        <div style="display:flex; align-items:center; gap:8px;">
                            <label style="font-size:13px;font-weight:600;color:#697386;">Month:</label>
                            <select id="fees-month-filter" class="filter-select" onchange="applyFeesFilter()" style="min-width:150px;">
                            </select>
                        </div>

                        <!-- Status Select -->
                        <div style="display:flex; align-items:center; gap:8px;">
                            <label style="font-size:13px;font-weight:600;color:#697386;">Status:</label>
                            <select id="fees-status-filter" class="filter-select" onchange="applyFeesFilter()">
                                <option value="all">All Students</option>
                                <option value="paid">✅ Paid Only</option>
                                <option value="unpaid">❌ Unpaid Only</option>
                            </select>
                        </div>

                        <!-- Search -->
                        <div class="search-wrapper" style="flex:1; min-width:200px;">
                            <svg class="search-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                            <input type="text" id="fees-students-search" class="search-input"
                                placeholder="Search student by name or email..." oninput="renderFeesMonthlyTable()">
                        </div>
                    </div>

                    <!-- Summary bar -->
                    <div id="fees-summary-bar" class="glass-card" style="padding:14px 20px; display:flex; gap:24px; align-items:center; margin-bottom:15px; display:none;">
                        <div>
                            <span style="font-size:12px;color:#697386;">Month</span><br>
                            <strong id="fees-month-label" style="color:#0077be;">—</strong>
                        </div>
                        <div style="width:1px;height:40px;background:rgba(0,0,0,0.08);"></div>
                        <div>
                            <span style="font-size:12px;color:#697386;">Due Date</span><br>
                            <strong id="fees-due-label" style="color:#1a1a1a;">—</strong>
                        </div>
                        <div style="width:1px;height:40px;background:rgba(0,0,0,0.08);"></div>
                        <div>
                            <span style="font-size:12px;color:#697386;">Amount</span><br>
                            <div id="fees-amount-container" style="display:flex; align-items:center; gap:6px;">
                                <strong id="fees-amount-label" style="color:#1a1a1a;">—</strong>
                                <button type="button" id="fees-amount-edit-btn" onclick="startEditFeeAmount()" style="background:none; border:none; color:#0077be; cursor:pointer; padding:2px; display:none; align-items:center;" title="Edit Fee Amount">
                                    <svg style="width:16px;height:16px;" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                    </svg>
                                </button>
                            </div>
                            <div id="fees-amount-edit-container" style="display:none; align-items:center; gap:4px; margin-top:2px;">
                                <input type="number" id="fees-amount-input" step="0.01" min="0" style="width:90px; padding:2px 6px; border:1px solid #0077be; border-radius:4px; font-size:13px; font-weight:600; color:#1a1a1a; background:rgba(255,255,255,0.9); outline:none;">
                                <button type="button" onclick="saveFeeAmountInline()" style="background:#0077be; border:none; color:white; padding:3px 8px; border-radius:4px; font-size:11px; cursor:pointer; font-weight:600; line-height:1.2;">Save</button>
                                <button type="button" onclick="cancelFeeAmountInline()" style="background:#697386; border:none; color:white; padding:3px 8px; border-radius:4px; font-size:11px; cursor:pointer; font-weight:600; line-height:1.2;">Cancel</button>
                            </div>
                        </div>
                        <div style="width:1px;height:40px;background:rgba(0,0,0,0.08);"></div>
                        <div>
                            <span style="font-size:12px;color:#4caf50;">✅ Paid</span><br>
                            <strong id="fees-paid-count">0</strong>
                        </div>
                        <div>
                            <span style="font-size:12px;color:#f44336;">❌ Unpaid</span><br>
                            <strong id="fees-unpaid-count">0</strong>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="glass-card" style="padding: 10px; overflow: hidden;">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Email</th>
                                        <th>Grade</th>
                                        <th>Payment Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="fees-students-table-body">
                                    <tr>
                                        <td colspan="5" style="text-align:center;color:#697386;padding:30px;">Select a month to view payment status.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- PANE 2: Teacher Salaries -->
                <div id="fees-teachers-pane" class="hidden">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; flex-wrap: wrap; gap: 10px;">
                        <h3 style="font-size:18px; font-weight:600; color:#1a1f36; margin:0;">Teacher Salaries Management</h3>
                        <div style="display:flex; gap:10px;">
                            <button class="btn-toggle-fee pay" onclick="exportSalariesCSV()" style="width: auto; padding: 10px 15px; font-weight: 600; font-size: 13px; background: #4CAF50; border: none; border-radius: 8px; color:white; cursor:pointer;">
                                Export CSV
                            </button>
                            <button class="btn-toggle-fee pay" onclick="printSalariesPDF()" style="width: auto; padding: 10px 15px; font-weight: 600; font-size: 13px; background: #0077be; border: none; border-radius: 8px; color:white; cursor:pointer;">
                                Print / PDF Report
                            </button>
                        </div>
                    </div>
                    
                    <!-- Filter Controls -->
                    <div class="controls-panel" style="gap: 12px; flex-wrap: wrap; margin-bottom:15px;">
                        <!-- Month Select -->
                        <div style="display:flex; align-items:center; gap:8px;">
                            <label style="font-size:13px;font-weight:600;color:#697386;">Month:</label>
                            <select id="salaries-month-filter" class="filter-select" onchange="applySalariesFilter()" style="min-width:150px;">
                            </select>
                        </div>

                        <!-- Status Select -->
                        <div style="display:flex; align-items:center; gap:8px;">
                            <label style="font-size:13px;font-weight:600;color:#697386;">Status:</label>
                            <select id="salaries-status-filter" class="filter-select" onchange="applySalariesFilter()">
                                <option value="all">All Teachers</option>
                                <option value="paid">✅ Paid Only</option>
                                <option value="unpaid">❌ Unpaid Only</option>
                            </select>
                        </div>

                        <!-- Search -->
                        <div class="search-wrapper" style="flex:1; min-width:200px;">
                            <svg class="search-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                            <input type="text" id="salaries-teachers-search" class="search-input"
                                placeholder="Search teacher by name or specialization..." oninput="renderSalariesMonthlyTable()">
                        </div>
                    </div>

                    <!-- Summary Bar -->
                    <div id="salaries-summary-bar" class="glass-card" style="padding:14px 20px; display:flex; gap:24px; align-items:center; margin-bottom:15px; display:none;">
                        <div>
                            <span style="font-size:12px;color:#697386;">Month</span><br>
                            <strong id="salaries-month-label" style="color:#0077be;">—</strong>
                        </div>
                        <div style="width:1px;height:40px;background:rgba(0,0,0,0.08);"></div>
                        <div>
                            <span style="font-size:12px;color:#697386;">Total Payroll</span><br>
                            <strong id="salaries-total-label" style="color:#1a1a1a;">LKR 0.00</strong>
                        </div>
                        <div style="width:1px;height:40px;background:rgba(0,0,0,0.08);"></div>
                        <div>
                            <span style="font-size:12px;color:#4caf50;">✅ Paid (Amount)</span><br>
                            <strong id="salaries-paid-amount" style="color:#4caf50;">LKR 0.00</strong>
                        </div>
                        <div style="width:1px;height:40px;background:rgba(0,0,0,0.08);"></div>
                        <div>
                            <span style="font-size:12px;color:#f44336;">❌ Unpaid (Amount)</span><br>
                            <strong id="salaries-unpaid-amount" style="color:#f44336;">LKR 0.00</strong>
                        </div>
                        <div style="width:1px;height:40px;background:rgba(0,0,0,0.08);"></div>
                        <div>
                            <span style="font-size:12px;color:#697386;">Staff Count</span><br>
                            <strong id="salaries-staff-counts">0 paid / 0 unpaid</strong>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="glass-card" style="padding: 10px; overflow: hidden;">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Teacher Name</th>
                                        <th>Employee ID</th>
                                        <th>Specialization</th>
                                        <th>Basic Salary</th>
                                        <th>Payment Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="salaries-table-body">
                                    <tr>
                                        <td colspan="6" style="text-align:center;color:#697386;padding:30px;">Select a month to view salary status.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VIEW: EMERGENCY ALERT BROADCAST & HISTORY -->
            <div id="view-emergency" class="view-panel hidden">
                <!-- Red Alert Banner -->
                <div style="background: linear-gradient(135deg, #ff4d4f 0%, #cf1322 100%); color: white; padding: 24px; border-radius: 20px; box-shadow: 0 10px 30px rgba(255,77,79,0.3); margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <h2 style="font-size: 22px; font-weight: 800; margin-bottom: 6px; display: flex; align-items: center; gap: 10px;">
                            <span>🚨</span> EMERGENCY BROADCAST CENTER
                        </h2>
                        <p style="font-size: 14px; opacity: 0.95; max-width: 650px;">
                            Instantly trigger a high-priority push notification and alarm sound to ALL Students and Teachers mobile devices. Use only in genuine emergency situations.
                        </p>
                    </div>
                    <div style="background: rgba(255,255,255,0.2); padding: 12px 20px; border-radius: 14px; text-align: center; backdrop-filter: blur(10px);">
                        <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; display: block; opacity: 0.9;">Target Audience</span>
                        <span style="font-size: 16px; font-weight: 800;">ALL USERS (Students &amp; Staff)</span>
                    </div>
                </div>

                <!-- Emergency Section Sub-Tabs -->
                <div style="display: flex; gap: 12px; margin-bottom: 24px; border-bottom: 2px solid rgba(0,0,0,0.06); padding-bottom: 12px;">
                    <button id="emergency-tab-trigger-btn" class="btn" style="background: #ff4d4f; color: white; border: none; padding: 10px 24px; font-weight: 700; border-radius: 10px; cursor: pointer;" onclick="switchEmergencySubTab('trigger')">
                        🚨 Trigger Emergency Alert
                    </button>
                    <button id="emergency-tab-history-btn" class="btn" style="background: rgba(0,0,0,0.05); color: #1a1f36; border: none; padding: 10px 24px; font-weight: 700; border-radius: 10px; cursor: pointer;" onclick="switchEmergencySubTab('history')">
                        📜 Emergency History Logs
                    </button>
                </div>

                <!-- SUB-TAB 1: TRIGGER EMERGENCY -->
                <div id="emergency-subtab-trigger">
                    <!-- One-Tap Emergency Presets -->
                    <div style="margin-bottom: 30px;">
                        <h3 style="font-size: 16px; font-weight: 700; color: #1a1f36; margin-bottom: 14px;">⚡ One-Tap Quick Emergency Presets</h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
                            
                            <!-- Fire Emergency -->
                            <div class="glass-card" style="padding: 20px; border-left: 5px solid #ff4d4f; cursor: pointer; transition: transform 0.2s;" onclick="triggerPresetEmergency('fire')">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                    <span style="font-size: 28px;">🔥</span>
                                    <span class="badge" style="background: rgba(255,77,79,0.1); color: #ff4d4f; font-weight: 700;">FIRE ALARM</span>
                                </div>
                                <h4 style="font-size: 16px; font-weight: 700; color: #1a1f36;">Fire Evacuation Alert</h4>
                                <p style="font-size: 13px; color: #697386; margin-top: 4px;">"Fire emergency reported on school grounds! Evacuate immediately to safe assembly zones."</p>
                                <button class="btn" style="background: #ff4d4f; color: white; border: none; width: 100%; margin-top: 14px; padding: 10px; font-weight: 700; border-radius: 10px;">
                                    🚨 BROADCAST FIRE ALARM
                                </button>
                            </div>

                            <!-- Severe Weather -->
                            <div class="glass-card" style="padding: 20px; border-left: 5px solid #fa8c16; cursor: pointer; transition: transform 0.2s;" onclick="triggerPresetEmergency('weather')">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                    <span style="font-size: 28px;">⛈️</span>
                                    <span class="badge" style="background: rgba(250,140,22,0.1); color: #fa8c16; font-weight: 700;">WEATHER ALERT</span>
                                </div>
                                <h4 style="font-size: 16px; font-weight: 700; color: #1a1f36;">Severe Weather Emergency</h4>
                                <p style="font-size: 13px; color: #697386; margin-top: 4px;">"Severe weather and heavy storm warning! Stay indoors and remain in designated shelter rooms."</p>
                                <button class="btn" style="background: #fa8c16; color: white; border: none; width: 100%; margin-top: 14px; padding: 10px; font-weight: 700; border-radius: 10px;">
                                    ⛈️ BROADCAST WEATHER ALERT
                                </button>
                            </div>

                            <!-- Campus Lockdown -->
                            <div class="glass-card" style="padding: 20px; border-left: 5px solid #722ed1; cursor: pointer; transition: transform 0.2s;" onclick="triggerPresetEmergency('lockdown')">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                    <span style="font-size: 28px;">🔒</span>
                                    <span class="badge" style="background: rgba(114,46,209,0.1); color: #722ed1; font-weight: 700;">SECURITY LOCKDOWN</span>
                                </div>
                                <h4 style="font-size: 16px; font-weight: 700; color: #1a1f36;">Security Lockdown Alert</h4>
                                <p style="font-size: 13px; color: #697386; margin-top: 4px;">"Campus Security Lockdown! Lock classroom doors, stay away from windows and follow instructions."</p>
                                <button class="btn" style="background: #722ed1; color: white; border: none; width: 100%; margin-top: 14px; padding: 10px; font-weight: 700; border-radius: 10px;">
                                    🔒 BROADCAST LOCKDOWN
                                </button>
                            </div>

                        </div>
                    </div>

                    <!-- Custom Emergency Broadcast Form -->
                    <div class="glass-card" style="padding: 28px; border-top: 4px solid #ff4d4f;">
                        <h3 style="font-size: 18px; font-weight: 700; color: #1a1f36; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                            <span>📢</span> Custom Emergency Notification Message
                        </h3>
                        <form id="emergency-form" onsubmit="handleSendEmergencyAlert(event)">
                            <div style="margin-bottom: 18px;">
                                <label style="display: block; font-weight: 600; font-size: 14px; margin-bottom: 6px;">Emergency Title</label>
                                <input type="text" id="emergency-title" class="search-input" required placeholder="e.g. Immediate Campus Evacuation Order" style="width: 100%; border:1px solid rgba(255,77,79,0.3); padding: 12px 16px; font-size: 15px;">
                            </div>
                            <div style="margin-bottom: 22px;">
                                <label style="display: block; font-weight: 600; font-size: 14px; margin-bottom: 6px;">Emergency Details / Message</label>
                                <textarea id="emergency-content" class="search-input" required rows="4" placeholder="Describe the emergency and safety instructions for students and teachers..." style="width: 100%; border:1px solid rgba(255,77,79,0.3); height:auto; padding: 14px; font-family:inherit; resize:vertical; font-size: 14px;"></textarea>
                            </div>
                            <div style="display: flex; justify-content: flex-end;">
                                <button type="submit" id="emergency-submit-btn" class="btn" style="background: linear-gradient(135deg, #ff4d4f, #cf1322); color: white; padding: 14px 32px; font-size: 16px; font-weight: 800; border: none; border-radius: 12px; box-shadow: 0 6px 20px rgba(255,77,79,0.4); cursor: pointer;">
                                    🚨 BROADCAST EMERGENCY ALERT TO ALL PHONES NOW
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- SUB-TAB 2: EMERGENCY HISTORY LOGS -->
                <div id="emergency-subtab-history" class="hidden">
                    <div class="glass-card" style="padding: 10px; overflow: hidden;">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Date &amp; Time</th>
                                        <th>Emergency Title</th>
                                        <th>Message / Content</th>
                                        <th>Preset Type</th>
                                        <th>Broadcasted By</th>
                                    </tr>
                                </thead>
                                <tbody id="emergency-history-table-body">
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: #697386; padding: 30px;">Loading emergency history...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VIEW: NOTICES -->
            <div id="view-notices" class="view-panel hidden">
                <div class="controls-panel" style="justify-content: space-between; align-items: center;">
                    <div class="search-wrapper" style="flex: 1; max-width: 300px; margin-bottom: 0;">
                        <svg class="search-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                        <input type="text" id="notices-search" class="search-input" placeholder="Search notices..." oninput="renderNoticesTable()">
                    </div>
                    <button id="btn-create-notice" class="btn btn-primary" onclick="openCreateNoticeModal()" style="width: auto; margin-top: 0; padding: 10px 20px; font-size: 13px;">
                        + Create Notice
                    </button>
                </div>

                <div class="glass-card" style="padding: 10px; overflow: hidden; margin-top: 20px;">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>Category</th>
                                    <th>Audience</th>
                                    <th>Expiry Date</th>
                                    <th id="th-notice-actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="notices-table-body">
                                <tr>
                                    <td colspan="6" style="text-align: center; color: #697386; padding: 30px;">Loading notices...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- VIEW: EVENTS -->
            <div id="view-events" class="view-panel hidden">
                <div class="controls-panel" style="justify-content: space-between; align-items: center;">
                    <div class="search-wrapper" style="flex: 1; max-width: 300px; margin-bottom: 0;">
                        <svg class="search-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                        <input type="text" id="events-search" class="search-input" placeholder="Search events..." oninput="renderEventsTable()">
                    </div>
                    <button id="btn-create-event" class="btn btn-primary" onclick="openCreateEventModal()" style="width: auto; margin-top: 0; padding: 10px 20px; font-size: 13px;">
                        + Create Event
                    </button>
                </div>

                <div class="glass-card" style="padding: 10px; overflow: hidden; margin-top: 20px;">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Location</th>
                                    <th id="th-event-actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="events-table-body">
                                <tr>
                                    <td colspan="7" style="text-align: center; color: #697386; padding: 30px;">Loading events...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- VIEW: EVENT GALLERY (ADMIN ONLY) -->
            <div id="view-gallery" class="view-panel hidden">
                <div class="view-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
                    <div>
                        <h2 style="margin:0; font-size:22px; font-weight:800; color:#1a1f36;"></h2>
                        <p style="margin:4px 0 0 0; font-size:13px; color:#697386;"></p>
                    </div>
                    <button class="btn btn-primary" onclick="openAddGalleryModal()" style="width: auto; margin-top: 0; padding: 12px 24px; font-size: 14px; font-weight:700; background: linear-gradient(135deg, #0077be, #005c99); border:none; border-radius:10px; color:white; cursor:pointer; display:flex; align-items:center; gap:8px; box-shadow: 0 4px 14px rgba(0,119,190,0.3);">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        + Add Gallery Event
                    </button>
                </div>

                <!-- Controls & Search -->
                <div class="controls-panel" style="margin-bottom:24px;">
                    <div class="search-wrapper" style="flex: 1; max-width: 380px; margin-bottom: 0;">
                        <svg class="search-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                        <input type="text" id="gallery-search" class="search-input" placeholder="Search event album name..." oninput="renderGalleryGrid()">
                    </div>
                </div>

                <!-- Gallery Events Container (Grid of Albums) -->
                <div id="gallery-events-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px;">
                    <div style="grid-column: 1 / -1; text-align: center; color: #697386; padding: 60px 20px;" class="glass-card">
                        Loading gallery albums...
                    </div>
                </div>
            </div>

            <!-- VIEW: COMPLAINTS -->
            <div id="view-complaints" class="view-panel hidden">
                <!-- Admin Section -->
                <div id="complaints-admin-section">
                    <div class="controls-panel">
                        <div class="search-wrapper">
                            <svg class="search-icon" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                            <input type="text" class="search-input" id="complaints-search" placeholder="Search complaints by name, category or message..." onkeyup="renderComplaintsTable()">
                        </div>
                        <select class="filter-select" id="complaints-status-filter" onchange="renderComplaintsTable()">
                            <option value="all">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    <div class="glass-card table-responsive" style="margin-top: 20px;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Submitted By</th>
                                    <th>Category</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Admin Response</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="complaints-table-body">
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Teacher Section: Create Complaint Form & History -->
                <div id="complaints-teacher-section" class="hidden">
                    <div style="max-width: 650px; margin: 0 auto; padding: 10px 0;">
                        <!-- Tab Switcher -->
                        <div style="display: flex; background: rgba(0,0,0,0.03); padding: 4px; border-radius: 12px; margin-bottom: 20px; width: fit-content; gap: 4px; border: 1px solid rgba(0,0,0,0.05);">
                            <button type="button" id="teacher-tab-new" onclick="switchTeacherComplaintsTab('new')" style="padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; background: #0077be; color: white; transition: all 0.25s ease;">New Complaint</button>
                            <button type="button" id="teacher-tab-history" onclick="switchTeacherComplaintsTab('history')" style="padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; background: transparent; color: #697386; transition: all 0.25s ease;">My History</button>
                        </div>

                        <!-- Tab 1: New Complaint Form -->
                        <div id="teacher-complaint-new-pane" class="glass-card" style="padding: 30px; border-radius: 16px;">
                            <h3 style="margin-bottom: 8px; font-weight: 700; color: #1a1f36;">Submit a Complaint / Feedback</h3>
                            <p style="color: #697386; font-size: 13px; margin-bottom: 24px; line-height: 1.5;">
                                Please submit details of your concern or suggestion below. The administration will review and address it.
                            </p>
                            
                            <form id="teacher-complaint-form" onsubmit="submitTeacherComplaint(event)">
                                <div style="margin-bottom: 20px;">
                                    <label style="display:block; font-size:13px; font-weight:600; color:#697386; margin-bottom:8px;">Category</label>
                                    <input type="text" id="teacher-complaint-category" class="search-input" placeholder="e.g., Academics, Transport, Facilities" 
                                        style="border:1.5px solid rgba(0,0,0,0.08); height:46px; border-radius: 12px; padding: 0 16px; width: 100%; box-sizing: border-box;" required>
                                </div>

                                <div style="margin-bottom: 24px;">
                                    <label style="display:block; font-size:13px; font-weight:600; color:#697386; margin-bottom:8px;">Message / Details</label>
                                    <textarea id="teacher-complaint-message" class="search-input" rows="6" placeholder="Describe your concern in detail..." 
                                        style="border:1.5px solid rgba(0,0,0,0.08); height:auto; padding:12px 16px; font-family:inherit; resize:vertical; border-radius: 12px; background: rgba(255, 255, 255, 0.8); width: 100%; box-sizing: border-box;" required></textarea>
                                </div>

                                <div style="display:flex; justify-content:center;">
                                    <button type="submit" class="btn btn-primary" style="padding: 14px 30px; font-size: 14px; font-weight: 700; border-radius: 12px; margin-top: 0; width: 100%; background: #4CAF50; border: none; color: white; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 12px rgba(76, 175, 80, 0.2);">
                                        Submit Complaint
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Tab 2: My History Pane -->
                        <div id="teacher-complaint-history-pane" class="hidden" style="display: flex; flex-direction: column; gap: 16px;">
                            <div id="teacher-complaints-history-list" style="display: flex; flex-direction: column; gap: 16px;">
                                <!-- Complaint cards will be rendered here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VIEW: LEAVE REQUESTS -->
            <div id="view-leaves" class="view-panel hidden">
                <!-- Admin Section: View and respond to leave requests -->
                <div id="leaves-admin-section">
                    <div class="controls-panel">
                        <div class="search-wrapper">
                            <svg class="search-icon" viewBox="0 0 24 24" style="width: 20px; height: 20px;"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                            <input type="text" class="search-input" id="leaves-search" placeholder="Search leave requests by type or reason..." onkeyup="renderLeavesTable()">
                        </div>
                        <select class="filter-select" id="leaves-status-filter" onchange="renderLeavesTable()">
                            <option value="all">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="glass-card table-responsive" style="margin-top: 20px; padding: 10px;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Teacher</th>
                                    <th>Leave Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Admin Response</th>
                                    <th>Submitted Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="leaves-table-body">
                                <tr>
                                    <td colspan="9" style="text-align: center; color: #697386; padding: 30px;">Loading leave requests...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Teacher Section: Create Leave Request & History -->
                <div id="leaves-teacher-section" class="hidden">
                    <div style="max-width: 650px; margin: 0 auto; padding: 10px 0;">
                        <!-- Tab Switcher -->
                        <div style="display: flex; background: rgba(0,0,0,0.03); padding: 4px; border-radius: 12px; margin-bottom: 20px; width: fit-content; gap: 4px; border: 1px solid rgba(0,0,0,0.05);">
                            <button type="button" id="teacher-leave-tab-new" onclick="switchTeacherLeavesTab('new')" style="padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; background: #0077be; color: white; transition: all 0.25s ease;">New Request</button>
                            <button type="button" id="teacher-leave-tab-history" onclick="switchTeacherLeavesTab('history')" style="padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; background: transparent; color: #697386; transition: all 0.25s ease;">My History</button>
                        </div>

                        <!-- Tab 1: New Leave Request Form -->
                        <div id="teacher-leave-new-pane" class="glass-card" style="padding: 30px; border-radius: 16px;">
                            <h3 style="margin-bottom: 8px; font-weight: 700; color: #1a1f36;">Request Leave</h3>
                            <p style="color: #697386; font-size: 13px; margin-bottom: 24px; line-height: 1.5;">
                                Submit details of your leave request. The administration will review and respond to it.
                            </p>
                            
                            <form id="teacher-leave-form" onsubmit="submitTeacherLeave(event)">
                                <div style="margin-bottom: 20px;">
                                    <label style="display:block; font-size:13px; font-weight:600; color:#697386; margin-bottom:8px;">Leave Type</label>
                                    <select id="teacher-leave-type" class="filter-select" style="width: 100%; border:1.5px solid rgba(0,0,0,0.08); height:46px; border-radius: 12px; box-sizing: border-box;" required>
                                        <option value="Sick Leave">Sick Leave</option>
                                        <option value="Casual Leave">Casual Leave</option>
                                        <option value="Annual Leave">Annual Leave</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div style="display: flex; gap: 16px; margin-bottom: 20px;">
                                    <div style="flex: 1;">
                                        <label style="display:block; font-size:13px; font-weight:600; color:#697386; margin-bottom:8px;">Start Date</label>
                                        <input type="date" id="teacher-leave-start" class="search-input" style="border:1.5px solid rgba(0,0,0,0.08); height:46px; border-radius: 12px; padding: 0 16px; width: 100%; box-sizing: border-box;" required>
                                    </div>
                                    <div style="flex: 1;">
                                        <label style="display:block; font-size:13px; font-weight:600; color:#697386; margin-bottom:8px;">End Date</label>
                                        <input type="date" id="teacher-leave-end" class="search-input" style="border:1.5px solid rgba(0,0,0,0.08); height:46px; border-radius: 12px; padding: 0 16px; width: 100%; box-sizing: border-box;" required>
                                    </div>
                                </div>

                                <div style="margin-bottom: 24px;">
                                    <label style="display:block; font-size:13px; font-weight:600; color:#697386; margin-bottom:8px;">Reason / Details</label>
                                    <textarea id="teacher-leave-reason" class="search-input" rows="6" placeholder="Describe the reason for your leave request..." 
                                        style="border:1.5px solid rgba(0,0,0,0.08); height:auto; padding:12px 16px; font-family:inherit; resize:vertical; border-radius: 12px; background: rgba(255, 255, 255, 0.8); width: 100%; box-sizing: border-box;" required></textarea>
                                </div>

                                <div style="display:flex; justify-content:center;">
                                    <button type="submit" class="btn btn-primary" style="padding: 14px 30px; font-size: 14px; font-weight: 700; border-radius: 12px; margin-top: 0; width: 100%; background: #4CAF50; border: none; color: white; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 12px rgba(76, 175, 80, 0.2);">
                                        Submit Request
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Tab 2: My History Pane -->
                        <div id="teacher-leave-history-pane" class="hidden" style="display: flex; flex-direction: column; gap: 16px;">
                            <div id="teacher-leaves-history-list" style="display: flex; flex-direction: column; gap: 16px;">
                                <!-- Leave cards will be rendered here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VIEW: TEACHER QR CODE -->
            <!-- VIEW: ADMIN TEACHER CONTENT -->
            <div id="view-admin-content" class="view-panel hidden">
                <!-- Tab buttons -->
                <div style="display:flex; gap:8px; margin-bottom:16px;">
                    <button id="admin-content-tab-materials" onclick="switchAdminContentTab('materials')"
                        style="padding:8px 20px; font-size:13px; font-weight:600; border:none; border-radius:8px; cursor:pointer; background:#0077be; color:white;">
                        📄 Materials
                    </button>
                    <button id="admin-content-tab-assignments" onclick="switchAdminContentTab('assignments')"
                        style="padding:8px 20px; font-size:13px; font-weight:600; border:none; border-radius:8px; cursor:pointer; background:rgba(0,0,0,0.06); color:#697386;">
                        📝 Assignments
                    </button>
                </div>

                <!-- Filters panel -->
                <div class="controls-panel" style="display:flex; gap:12px; margin-bottom:18px; align-items:center; flex-wrap:wrap; background:rgba(255,255,255,0.6); padding:12px 16px; border-radius:12px; border:1px solid rgba(255,255,255,0.4);">
                    <div class="search-wrapper" style="flex:1; min-width:200px; margin:0;">
                        <svg class="search-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 14z"/></svg>
                        <input type="text" id="admin-content-search" class="search-input" placeholder="Search teacher, subject, title..." oninput="filterAdminContent()">
                    </div>

                    <select id="admin-content-filter-grade" class="search-input" style="width:160px; padding:0 12px; background:white; border:1px solid rgba(0,0,0,0.1); border-radius:8px; height:40px;" onchange="filterAdminContent()">
                        <option value="">All Grades</option>
                    </select>

                    <select id="admin-content-filter-subject" class="search-input" style="width:180px; padding:0 12px; background:white; border:1px solid rgba(0,0,0,0.1); border-radius:8px; height:40px;" onchange="filterAdminContent()">
                        <option value="">All Subjects</option>
                    </select>

                    <select id="admin-content-filter-teacher" class="search-input" style="width:180px; padding:0 12px; background:white; border:1px solid rgba(0,0,0,0.1); border-radius:8px; height:40px;" onchange="filterAdminContent()">
                        <option value="">All Teachers</option>
                    </select>

                    <div id="admin-content-type-wrapper" style="display:inline-block;">
                        <select id="admin-materials-type" class="search-input" style="width:140px; padding:0 12px; background:white; border:1px solid rgba(0,0,0,0.1); border-radius:8px; height:40px;" onchange="filterAdminContent()">
                            <option value="">All Types</option>
                            <option value="pdf">PDF</option>
                            <option value="video">Video</option>
                            <option value="image">Image</option>
                            <option value="assignment">Assignment</option>
                        </select>
                    </div>
                </div>

                <!-- Materials pane -->
                <div id="admin-content-materials-pane">
                    <div class="glass-card" style="padding:10px; overflow:hidden;">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Teacher</th>
                                        <th>Subject</th>
                                        <th>Grade</th>
                                        <th>Topic Name</th>
                                        <th>Type</th>
                                        <th>Uploaded</th>
                                        <th>File</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="admin-materials-table-body">
                                    <tr><td colspan="8" style="text-align:center;color:#697386;padding:30px;">Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Assignments pane -->
                <div id="admin-content-assignments-pane" style="display:none;">
                    <div class="glass-card" style="padding:10px; overflow:hidden;">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Teacher</th>
                                        <th>Subject</th>
                                        <th>Grade</th>
                                        <th>Title</th>
                                        <th>Due Date</th>
                                        <th>Total Marks</th>
                                        <th>Uploaded</th>
                                        <th>File</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="admin-assignments-table-body">
                                    <tr><td colspan="9" style="text-align:center;color:#697386;padding:30px;">Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VIEW: TEACHER QR -->
            <div id="view-teacher-qr" class="view-panel hidden">
                <div style="display: flex; justify-content: center; align-items: center; min-height: 400px; padding: 20px;">
                    <div class="glass-card" style="max-width: 400px; text-align: center; padding: 30px; border-radius: 20px; width: 100%;">
                        <h3 style="font-size: 20px; font-weight: 700; color: #1a1f36; margin-bottom: 5px;">My Attendance QR Code</h3>
                        <p style="font-size: 13px; color: #697386; margin-bottom: 25px;">Show or download this QR code to mark your daily school attendance.</p>
                        
                        <div style="background: white; padding: 20px; border-radius: 16px; display: inline-block; box-shadow: 0 8px 24px rgba(0,0,0,0.06); margin-bottom: 25px; border: 1px solid rgba(0,0,0,0.05);">
                            <img id="teacher-qr-image" src="" alt="My QR Code" style="width: 220px; height: 220px; display: block;">
                        </div>
                        
                        <div id="teacher-qr-details" style="margin-bottom: 30px;">
                            <div style="font-size: 16px; font-weight: 700; color: #1a1f36;" id="teacher-qr-name">—</div>
                            <div style="font-size: 12px; font-weight: 600; color: #9c27b0; margin-top: 4px; text-transform: uppercase;" id="teacher-qr-role">TEACHER</div>
                        </div>
                        
                        <button class="btn btn-primary" onclick="downloadTeacherQrCode()" style="width: 100%; height: 46px; font-weight: 700; font-size: 14px; border-radius: 10px; margin-top: 0; background: #0077be; border: none; color: white;">
                            DOWNLOAD QR CODE
                        </button>
                    </div>
                </div>
            </div>

            <!-- VIEW: TEACHER TIMETABLE -->
            <div id="view-teacher-timetable" class="view-panel hidden">
                <div class="controls-panel" style="justify-content: flex-start; gap: 15px;">
                    <div style="font-weight: 600; color: #1a1f36;">Filter Day:</div>
                    <div class="day-selector-pills" style="display: flex; gap: 8px;">
                        <button class="pill active" onclick="selectTeacherTimetableDay('Monday')">Mon</button>
                        <button class="pill" onclick="selectTeacherTimetableDay('Tuesday')">Tue</button>
                        <button class="pill" onclick="selectTeacherTimetableDay('Wednesday')">Wed</button>
                        <button class="pill" onclick="selectTeacherTimetableDay('Thursday')">Thu</button>
                        <button class="pill" onclick="selectTeacherTimetableDay('Friday')">Fri</button>
                        <button class="pill" onclick="selectTeacherTimetableDay('Saturday')">Sat</button>
                        <button class="pill" onclick="selectTeacherTimetableDay('Sunday')">Sun</button>
                    </div>
                </div>
                
                <div class="glass-card" style="padding: 20px;">
                    <h3 style="margin-bottom: 20px; font-weight: 700; color: #1a1f36; display: flex; align-items: center; gap: 10px;">
                        <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; fill: #0077be;"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/></svg>
                        Schedule for <span id="teacher-timetable-day-name">Monday</span>
                    </h3>
                    <div id="teacher-timetable-timeline" class="timeline-container">
                        <!-- Dynamic timeline cards -->
                    </div>
                </div>
            </div>

            <!-- VIEW: MEAL PLAN -->
            <div id="view-meal-plan" class="view-panel hidden">
                <div class="controls-panel" style="justify-content: flex-start; gap: 15px;">
                    <div style="font-weight: 600; color: #1a1f36;">Select Day:</div>
                    <div class="day-selector-pills" id="meal-plan-day-pills" style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <button class="pill active" onclick="selectMealPlanDay('Monday')">Mon</button>
                        <button class="pill" onclick="selectMealPlanDay('Tuesday')">Tue</button>
                        <button class="pill" onclick="selectMealPlanDay('Wednesday')">Wed</button>
                        <button class="pill" onclick="selectMealPlanDay('Thursday')">Thu</button>
                        <button class="pill" onclick="selectMealPlanDay('Friday')">Fri</button>
                        <button class="pill" onclick="selectMealPlanDay('Saturday')">Sat</button>
                        <button class="pill" onclick="selectMealPlanDay('Sunday')">Sun</button>
                    </div>
                </div>

                <div class="glass-card" style="padding: 25px; max-width: 700px;">
                    <h3 style="margin-bottom: 20px; font-weight: 700; color: #1a1f36; display: flex; align-items: center; gap: 10px;">
                        <svg viewBox="0 0 24 24" style="width: 24px; height: 24px; fill: #0077be;"><path d="M8.1 13.34l2.83-2.83L3.91 3.5c-1.56 1.56-1.56 4.09 0 5.66l4.19 4.18zm6.78-1.81c1.53.71 3.68.21 5.27-1.38 1.91-1.91 2.28-4.65.81-6.12-1.46-1.46-4.2-1.1-6.12.81-1.59 1.59-2.09 3.74-1.38 5.27L3.7 19.87l1.41 1.41L12 14.41l6.88 6.88 1.41-1.41L13.41 13l1.47-1.47z"/></svg>
                        Meal Plan for <span id="meal-plan-day-name">Monday</span>
                    </h3>

                    <form id="meal-plan-form" onsubmit="saveMealPlan(event)">
                        <div style="margin-bottom: 16px;">
                            <label style="display:block; margin-bottom:6px; font-size:13px; font-weight:600; color:#697386;">🍽️ Meal</label>
                            <textarea id="meal-plan-meal" class="search-input" rows="5" placeholder="e.g. Breakfast: Milk rice, dhal curry, fruits&#10;Lunch: Rice, chicken curry, vegetables&#10;Snack: Biscuits, juice" style="border:1px solid rgba(0,0,0,0.15); width:100%; height:auto; padding:10px; font-family:inherit; resize:vertical;"></textarea>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display:block; margin-bottom:6px; font-size:13px; font-weight:600; color:#697386;">📝 Notes</label>
                            <textarea id="meal-plan-notes" class="search-input" rows="2" placeholder="Allergy notes, special instructions..." style="border:1px solid rgba(0,0,0,0.15); width:100%; height:auto; padding:10px; font-family:inherit; resize:vertical;"></textarea>
                        </div>
                        <div id="meal-plan-last-updated" style="font-size:12px; color:#94a3b8; margin-bottom:14px;"></div>
                        <button type="submit" id="meal-plan-save-btn" class="btn btn-primary" style="width:auto; padding: 12px 28px;">SAVE MEAL PLAN</button>
                    </form>
                    <div id="meal-plan-readonly-note" class="hidden" style="font-size:12px; color:#94a3b8; margin-top:10px;">Only administrators can edit the meal plan.</div>
                </div>
            </div>

            <!-- VIEW: TEACHER UPLOAD MATERIALS -->
            <div id="view-teacher-materials" class="view-panel hidden">
                <div class="glass-card" style="max-width: 700px; margin: 0 auto; padding: 25px;">
                    <h3 style="margin-bottom: 20px; font-weight: 700; color: #1a1f36;">Upload Class Materials & Assignments</h3>
                    
                    <!-- Beautiful Tabs for Materials vs Assignments -->
                    <div style="display: flex; gap: 10px; margin-bottom: 25px; background: rgba(0,0,0,0.03); padding: 5px; border-radius: 10px;">
                        <button id="btn-upload-material-tab" onclick="switchUploadTab('material')" style="flex: 1; padding: 12px; font-size: 14px; font-weight: 700; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s; background: #0077be; color: white; box-shadow: 0 4px 6px rgba(0,119,190,0.2);">
                            📚 Upload Study Material
                        </button>
                        <button id="btn-upload-assignment-tab" onclick="switchUploadTab('assignment')" style="flex: 1; padding: 12px; font-size: 14px; font-weight: 700; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s; background: transparent; color: #697386;">
                            📝 Upload Assignment
                        </button>
                    </div>

                    <!-- PANE 1: UPLOAD MATERIAL -->
                    <div id="pane-upload-material">
                        <form id="teacher-material-form" onsubmit="handleUploadMaterial(event)">
                            <div style="margin-bottom: 16px;">
                                <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Target Grade</label>
                                <select id="material-grade" class="filter-select" style="width: 100%; border:1px solid rgba(0,0,0,0.15); height:42px; background: white;" required>
                                    <option value="">Loading grades...</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 16px;">
                                <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Select Subject</label>
                                <select id="material-subject-select" class="filter-select" style="width: 100%; border:1px solid rgba(0,0,0,0.15); height:42px; background: white;" required>
                                    <option value="">Loading subjects...</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 16px;">
                                <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Topic Name / Description</label>
                                <input type="text" id="material-topic-desc" class="search-input" required placeholder="e.g. Fractions Introduction" style="border:1px solid rgba(0,0,0,0.15);">
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Instruction PDF File</label>
                                <input type="file" id="material-pdf" accept="application/pdf" style="margin-top: 5px;" required>
                            </div>
                            <button type="submit" class="btn-toggle-fee pay" style="width: 100%; height: 45px; background: #0077be; font-size: 15px; font-weight: bold; border: none; border-radius: 10px; color: white; cursor: pointer;">
                                Create &amp; Upload Material
                            </button>
                        </form>
                    </div>

                    <!-- PANE 2: UPLOAD ASSIGNMENT -->
                    <div id="pane-upload-assignment" class="hidden">
                        <form id="teacher-assignment-form" onsubmit="handleUploadAssignment(event)">
                            <div style="margin-bottom: 16px;">
                                <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Target Grade</label>
                                <select id="assignment-grade" class="filter-select" style="width: 100%; border:1px solid rgba(0,0,0,0.15); height:42px; background: white;" required>
                                    <option value="">Loading grades...</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 16px;">
                                <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Select Subject</label>
                                <select id="assignment-subject-select" class="filter-select" style="width: 100%; border:1px solid rgba(0,0,0,0.15); height:42px; background: white;" required>
                                    <option value="">Loading subjects...</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 16px;">
                                <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Assignment Title</label>
                                <input type="text" id="assignment-title" class="search-input" required placeholder="e.g. Fractions Homework Sheet" style="border:1px solid rgba(0,0,0,0.15);">
                            </div>
                            <div style="margin-bottom: 16px;">
                                <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Due Date & Time</label>
                                <input type="datetime-local" id="assignment-due-time" class="search-input" required style="border:1px solid rgba(0,0,0,0.15); background: white; height: 42px; padding: 0 12px;">
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Assignment PDF File</label>
                                <input type="file" id="assignment-pdf" accept="application/pdf" style="margin-top: 5px;" required>
                            </div>
                            <button type="submit" class="btn-toggle-fee pay" style="width: 100%; height: 45px; background: #9c27b0; font-size: 15px; font-weight: bold; border: none; border-radius: 10px; color: white; cursor: pointer;">
                                Create &amp; Upload Assignment
                            </button>
                        </form>
                    </div>

                </div>
            </div>

            <!-- VIEW: STUDENT ASSIGNMENT SUBMISSIONS -->
            <div id="view-teacher-submissions" class="view-panel hidden">
                <div class="controls-panel" style="display:flex; gap:15px; margin-bottom:20px; align-items:center; background: rgba(255,255,255,0.5); padding:15px; border-radius:12px; border:1px solid rgba(255,255,255,0.3);">
                    <div class="search-wrapper" style="flex:1; min-width:200px;">
                        <svg class="search-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                        <input type="text" id="teacher-submissions-search" class="search-input" placeholder="Search submissions by student name or subject...">
                    </div>
                    <select id="teacher-submissions-grade-filter" class="search-input" style="width:200px; padding:0 12px; background:white; border:1px solid rgba(0,0,0,0.1); border-radius:8px; height:40px;">
                        <option value="">All Grades</option>
                    </select>
                </div>
                
                <div class="glass-card" style="padding: 10px; overflow: hidden;">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Grade (Class)</th>
                                    <th>Subject</th>
                                    <th>Submitted File</th>
                                    <th>Submitted Date</th>
                                    <th>Grade</th>
                                    <th>Feedback</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="teacher-submissions-table-body">
                                <tr>
                                    <td colspan="8" style="text-align: center; color: #697386; padding: 30px;">Loading student submissions...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- VIEW: TEACHER SALARIES -->
            <div id="view-teacher-salaries" class="view-panel hidden">
                <div class="glass-card" style="padding: 20px; margin-bottom: 20px;">
                    <div style="display: flex; gap: 20px; align-items: center;">
                        <div class="metric-icon-box bg-green" style="width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" style="color: white;"><path d="M21 18v1c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2V5c0-1.1.89-2 2-2h14c1.1 0 2 .9 2 2v1h-9c-1.11 0-2 .9-2 2v8c0 1.1.89 2 2 2h9zm-9-2h10V8H12v8zm4-2.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>
                        </div>
                        <div>
                            <div style="font-size: 13px; color: #697386; font-weight: 500;">Basic Salary</div>
                            <div class="metric-value" style="font-size: 24px; font-weight: 700; color: #1a1f36;" id="teacher-basic-salary">LKR 0.00</div>
                        </div>
                    </div>
                </div>
                
                <div class="glass-card" style="padding: 10px; overflow: hidden;">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Payment Ref</th>
                                    <th>Period (Month/Year)</th>
                                    <th>Paid Amount</th>
                                    <th>Payment Date</th>
                                    <th>Payment Method</th>
                                    <th>Notes</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="teacher-salaries-table-body">
                                <tr>
                                    <td colspan="7" style="text-align: center; color: #697386; padding: 30px;">Loading salary history...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- VIEW: EXAM RESULTS (TEACHER) -->
            <div id="view-exam-results" class="view-panel hidden">
                <div class="view-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <h2>Exam Results Management</h2>
                    <div style="display:flex; gap:10px;">
                        <button class="btn-toggle-fee pay" onclick="exportExamResultsCSV()" style="width: auto; padding: 10px 15px; font-weight: 600; font-size: 13px; background: #4CAF50; border: none; border-radius: 8px; color:white; cursor:pointer;">
                            Export CSV
                        </button>
                        <button class="btn-toggle-fee pay" onclick="printExamResultsPDF()" style="width: auto; padding: 10px 15px; font-weight: 600; font-size: 13px; background: #0077be; border: none; border-radius: 8px; color:white; cursor:pointer;">
                            Print / PDF Report
                        </button>
                        <button class="btn-toggle-fee pay" onclick="openExamResultModal()" style="width: auto; padding: 10px 15px; font-weight: 600; font-size: 13px; background: #E91E63; border: none; border-radius: 8px; color:white; cursor:pointer;">
                            + Add Exam Result
                        </button>
                    </div>
                </div>

                <div id="exam-records-section">
                    <div class="controls-panel" style="display:flex; gap:15px; margin-bottom:20px; align-items:center; background: rgba(255,255,255,0.5); padding:15px; border-radius:12px; border:1px solid rgba(255,255,255,0.3);">
                        <div class="search-wrapper" style="flex:1; min-width:200px;">
                            <svg class="search-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                            <input type="text" id="exam-results-search" class="search-input" placeholder="Search student name...">
                        </div>
                        
                        <select id="exam-results-filter-class" class="search-input" style="width:150px; padding:0 12px; background:white; border:1px solid rgba(0,0,0,0.1); border-radius:8px; height:40px;">
                            <option value="">All Classes</option>
                        </select>
                        
                        <select id="exam-results-filter-subject" class="search-input" style="width:200px; padding:0 12px; background:white; border:1px solid rgba(0,0,0,0.1); border-radius:8px; height:40px;">
                            <option value="">All Subjects</option>
                        </select>
                        
                        <select id="exam-results-filter-term" class="search-input" style="width:150px; padding:0 12px; background:white; border:1px solid rgba(0,0,0,0.1); border-radius:8px; height:40px;">
                            <option value="">All Terms</option>
                            <option value="Term 1">Term 1</option>
                            <option value="Term 2">Term 2</option>
                            <option value="Term 3">Term 3</option>
                        </select>

                        <select id="exam-results-filter-year" class="search-input" style="width:120px; padding:0 12px; background:white; border:1px solid rgba(0,0,0,0.1); border-radius:8px; height:40px;">
                            <option value="">All Years</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                        </select>
                    </div>

                    <div class="glass-card" style="padding: 10px; overflow: hidden;">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Class</th>
                                        <th>Subject</th>
                                        <th>Exam Name</th>
                                        <th>Term</th>
                                        <th>Year</th>
                                        <th>Marks</th>
                                        <th>Grade</th>
                                        <th>Remarks</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="exam-results-table-body">
                                    <tr>
                                        <td colspan="10" style="text-align: center; color: #697386; padding: 30px;">Loading exam results...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VIEW: CLASS RANKINGS -->
            <div id="view-class-rankings" class="view-panel hidden">
                <div class="view-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <h2></h2>
                    <div style="display:flex; gap:10px;">
                        <button class="btn-toggle-fee pay" onclick="printClassRankingsPDF()" style="width: auto; padding: 10px 15px; font-weight: 600; font-size: 13px; background: #0077be; border: none; border-radius: 8px; color:white; cursor:pointer;">
                            Print / PDF Report
                        </button>
                    </div>
                </div>

                <div class="controls-panel" style="display:flex; gap:15px; margin-bottom:20px; align-items:center; background: rgba(255,255,255,0.5); padding:15px; border-radius:12px; border:1px solid rgba(255,255,255,0.3);">
                    <select id="rankings-filter-grade" class="search-input" style="width:200px; padding:0 12px; background:white; border:1px solid rgba(0,0,0,0.1); border-radius:8px; height:40px;" onchange="renderClassRankings()">
                        <option value="">Select Grade</option>
                    </select>
                    
                    <select id="rankings-filter-term" class="search-input" style="width:200px; padding:0 12px; background:white; border:1px solid rgba(0,0,0,0.1); border-radius:8px; height:40px;" onchange="renderClassRankings()">
                        <option value="">Select Term</option>
                        <option value="Term 1">Term 1</option>
                        <option value="Term 2">Term 2</option>
                        <option value="Term 3">Term 3</option>
                    </select>

                    <select id="rankings-filter-year" class="search-input" style="width:150px; padding:0 12px; background:white; border:1px solid rgba(0,0,0,0.1); border-radius:8px; height:40px;" onchange="renderClassRankings()">
                        <option value="">Select Year</option>
                        <option value="2026">2026</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                    </select>
                </div>

                <div class="glass-card" style="padding: 10px; overflow: hidden;">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 130px; text-align: center;">Rank / Position</th>
                                    <th>Student Name</th>
                                    <th>Grade (Class)</th>
                                    <th>Total Marks</th>
                                    <th>Average %</th>
                                    <th>Subjects</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="rankings-table-body">
                                <tr>
                                    <td colspan="7" style="text-align: center; color: #697386; padding: 30px;">Please select a Grade, Term, and Year to view rankings.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- VIEW: AI PERFORMANCE INSIGHTS -->
            <div id="view-ai-performance" class="view-panel hidden">
                <div class="view-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
                    <div>
                        <h2 style="display:flex; align-items:center; gap:10px; margin:0;">
                            <span style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:10px; background:linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%); color:white;">
                                <svg style="width:20px; height:20px; fill:currentColor;" viewBox="0 0 24 24"><path d="M19 9l1.25-2.75L23 5l-2.75-1.25L19 1l-1.25 2.75L15 5l2.75 1.25L19 9zm-7.5.5L9 4 6.5 9.5 1 12l5.5 2.5L9 20l2.5-5.5L17 12l-5.5-2.5zM19 15l-1.25 2.75L15 19l2.75 1.25L19 23l1.25-2.75L23 19l-2.75-1.25L19 15z"/></svg>
                            </span>
                            AI Performance Insights
                        </h2>
                        <p style="margin:4px 0 0 0; font-size:13px; color:#697386;">Student academic performance analysis, strengths, weaknesses and progress evaluation powered by Smart School AI</p>
                    </div>
                    <div style="display:flex; gap:10px; align-items:center;">
                        <button id="ai-perf-download-btn" class="btn-toggle-fee pay hidden" onclick="downloadAiPerformancePdf()" style="width: auto; padding: 10px 16px; font-weight: 600; font-size: 13px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border: none; border-radius: 8px; color:white; cursor:pointer; display:inline-flex; align-items:center; gap:6px; box-shadow: 0 4px 10px rgba(99,102,241,0.25);">
                            <svg style="width:16px; height:16px; fill:currentColor;" viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                            Download PDF
                        </button>
                        <button id="ai-perf-print-btn" class="btn-toggle-fee pay hidden" onclick="printAiPerformanceReport()" style="width: auto; padding: 10px 16px; font-weight: 600; font-size: 13px; background: #0077be; border: none; border-radius: 8px; color:white; cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
                            <svg style="width:16px; height:16px; fill:currentColor;" viewBox="0 0 24 24"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
                            Print Report
                        </button>
                    </div>
                </div>

                <!-- CONTROLS & FILTERS -->
                <div class="glass-card" style="padding:16px 20px; margin-bottom:20px; background:rgba(255,255,255,0.7); border-radius:12px; border:1px solid rgba(255,255,255,0.4); box-shadow:0 4px 15px rgba(0,0,0,0.03);">
                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)) auto; gap:15px; align-items:end;">
                        <!-- Grade Filter -->
                        <div>
                            <label style="display:block; font-size:12px; font-weight:700; color:#475569; margin-bottom:6px;">Grade / Class</label>
                            <select id="ai-perf-grade" class="search-input" style="width:100%; padding:0 12px; background:white; border:1.5px solid #d1d9e0; border-radius:8px; height:42px; font-size:13px; font-weight:600;" onchange="onAiPerfGradeChanged()">
                                <option value="">Select Grade</option>
                            </select>
                        </div>

                        <!-- Student Name Filter (Dynamically populated by grade) -->
                        <div>
                            <label style="display:block; font-size:12px; font-weight:700; color:#475569; margin-bottom:6px;">Student Name</label>
                            <select id="ai-perf-student" class="search-input" style="width:100%; padding:0 12px; background:white; border:1.5px solid #d1d9e0; border-radius:8px; height:42px; font-size:13px; font-weight:600;" disabled onchange="onAiPerfStudentChanged()">
                                <option value="">Select Student</option>
                            </select>
                        </div>

                        <!-- Year Filter -->
                        <div>
                            <label style="display:block; font-size:12px; font-weight:700; color:#475569; margin-bottom:6px;">Academic Year</label>
                            <select id="ai-perf-year" class="search-input" style="width:100%; padding:0 12px; background:white; border:1.5px solid #d1d9e0; border-radius:8px; height:42px; font-size:13px; font-weight:600;" onchange="onAiPerfFiltersChanged()">
                                <option value="2026">2026</option>
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                            </select>
                        </div>

                        <!-- Term Filter (Optional) -->
                        <div>
                            <label style="display:block; font-size:12px; font-weight:700; color:#475569; margin-bottom:6px;">Term (Optional)</label>
                            <select id="ai-perf-term" class="search-input" style="width:100%; padding:0 12px; background:white; border:1.5px solid #d1d9e0; border-radius:8px; height:42px; font-size:13px; font-weight:600;" onchange="onAiPerfFiltersChanged()">
                                <option value="All Terms">All Terms (Cumulative)</option>
                                <option value="Term 1">Term 1</option>
                                <option value="Term 2">Term 2</option>
                                <option value="Term 3">Term 3</option>
                            </select>
                        </div>

                        <!-- Generate Button -->
                        <div>
                            <button id="ai-perf-generate-btn" class="btn-toggle-fee pay" onclick="triggerGenerateAiReport()" style="width: 100%; height:42px; padding: 0 20px; font-weight: 700; font-size: 13px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%); border: none; border-radius: 8px; color:white; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:8px; box-shadow:0 4px 12px rgba(99,102,241,0.3); transition:all 0.25s ease;" disabled>
                                <svg style="width:16px; height:16px; fill:currentColor;" viewBox="0 0 24 24"><path d="M19 9l1.25-2.75L23 5l-2.75-1.25L19 1l-1.25 2.75L15 5l2.75 1.25L19 9zm-7.5.5L9 4 6.5 9.5 1 12l5.5 2.5L9 20l2.5-5.5L17 12l-5.5-2.5zM19 15l-1.25 2.75L15 19l2.75 1.25L19 23l1.25-2.75L23 19l-2.75-1.25L19 15z"/></svg>
                                <span>Generate Report</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- REPORT AREA (Empty by default) -->
                <div id="ai-perf-report-container">
                    <div id="ai-perf-empty-state" class="glass-card" style="text-align: center; padding: 60px 20px; background: rgba(255,255,255,0.6); border-radius: 16px; border: 1.5px dashed #cbd5e1;">
                        <div style="width: 72px; height: 72px; margin: 0 auto 16px auto; border-radius: 50%; background: linear-gradient(135deg, rgba(99,102,241,0.15), rgba(217,70,239,0.15)); display: flex; align-items: center; justify-content: center; font-size: 32px;">
                            ✨
                        </div>
                        <h3 style="color: #1e293b; font-size: 18px; margin: 0 0 8px 0; font-weight: 700;">AI Academic Performance Insight</h3>
                        <p style="color: #64748b; font-size: 14px; max-width: 480px; margin: 0 auto 15px auto; line-height: 1.5;">
                            Select a Grade and Student from the filters above to view or generate an AI-powered academic performance analysis.
                        </p>
                        <span style="display:inline-block; font-size:12px; font-weight:600; color:#8b5cf6; background:rgba(139,92,246,0.1); padding:4px 12px; border-radius:20px;">
                            Powered by Smart School AI
                        </span>
                    </div>

                    <div id="ai-perf-loading-state" class="glass-card hidden" style="text-align: center; padding: 60px 20px; background: rgba(255,255,255,0.8); border-radius: 16px;">
                        <div class="spinner" style="width: 44px; height: 44px; margin: 0 auto 16px auto; border-width: 4px; border-color: #e2e8f0; border-top-color: #8b5cf6; border-radius:50%; animation: spin 1s linear infinite;"></div>
                        <h3 style="color: #1e293b; font-size: 17px; margin: 0 0 6px 0; font-weight: 700;">Analyzing Student Academic Data...</h3>
                        <p style="color: #64748b; font-size: 13px; margin: 0;">Exam results and assignment records are being evaluated with Smart School AI.</p>
                    </div>

                    <!-- ACTIVE REPORT VIEW -->
                    <div id="ai-perf-content-state" class="hidden">
                        <!-- Populated by JavaScript -->
                    </div>
                </div>
            </div>

    <!-- VIEW: TEACHER CLASS RESULTS (MY CLASS ALL SUBJECT RESULTS) -->
    <div id="view-teacher-class-results" class="view-panel hidden">
        <div class="view-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <div>
                <h2 style="margin:0; font-size:20px; color:#1a1f36;"></h2>
                <p style="margin:4px 0 0 0; color:#697386; font-size:13px;"></p>
            </div>
            <div style="display:flex; gap:10px;">
                <button class="btn-toggle-fee pay" onclick="printTeacherClassResultsPDF()" style="width: auto; padding: 10px 15px; font-weight: 600; font-size: 13px; background: #0077be; border: none; border-radius: 8px; color:white; cursor:pointer;">
                    Print Class Report Sheet
                </button>
            </div>
        </div>

        <div class="controls-panel" style="display:flex; gap:15px; margin-bottom:20px; align-items:center; background: rgba(255,255,255,0.5); padding:15px; border-radius:12px; border:1px solid rgba(255,255,255,0.3);">
            <div class="search-wrapper" style="flex:1; min-width:200px;">
                <svg class="search-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                <input type="text" id="teacher-class-results-search" class="search-input" placeholder="Search student name..." oninput="renderTeacherClassResults()">
            </div>

            <select id="teacher-class-results-grade" class="search-input" style="width:160px; padding:0 12px; background:white; border:1px solid rgba(0,0,0,0.1); border-radius:8px; height:40px;" onchange="renderTeacherClassResults()">
                <option value="">Select Grade</option>
            </select>
            
            <select id="teacher-class-results-term" class="search-input" style="width:150px; padding:0 12px; background:white; border:1px solid rgba(0,0,0,0.1); border-radius:8px; height:40px;" onchange="renderTeacherClassResults()">
                <option value="Term 1">Term 1</option>
                <option value="Term 2">Term 2</option>
                <option value="Term 3">Term 3</option>
            </select>

            <select id="teacher-class-results-year" class="search-input" style="width:120px; padding:0 12px; background:white; border:1px solid rgba(0,0,0,0.1); border-radius:8px; height:40px;" onchange="renderTeacherClassResults()">
                <option value="2026">2026</option>
                <option value="2027">2027</option>
            </select>
        </div>

        <div id="teacher-class-results-container">
            <!-- Dynamic student results container -->
        </div>
    </div>

    <!-- STUDENT RANKING MARKS MODAL -->
    <div id="ranking-student-detail-modal" class="modal-overlay hidden">
        <div class="modal-container glass-card" style="margin: auto; max-width: 650px;">
            <div class="modal-header">
                <h3 id="ranking-modal-title">Student Exam Report</h3>
                <button class="modal-close-btn" onclick="closeRankingStudentDetailModal()">&times;</button>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <div style="display:flex; justify-content:space-between; margin-bottom:15px; background:rgba(0,119,190,0.05); padding:12px; border-radius:8px;">
                    <div><strong>Rank Position:</strong> <span id="ranking-modal-rank" style="font-weight:bold; color:#0077be;"></span></div>
                    <div><strong>Total Score:</strong> <span id="ranking-modal-score" style="font-weight:bold; color:#4CAF50;"></span></div>
                    <div><strong>Average:</strong> <span id="ranking-modal-avg" style="font-weight:bold; color:#ff9800;"></span></div>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Exam Name</th>
                                <th>Marks Obtained</th>
                                <th>Grade</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody id="ranking-student-detail-body">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

            <!-- VIEW: TEACHER UPLOADED MATERIALS -->
            <div id="view-teacher-uploaded-materials" class="view-panel hidden">
                <div class="glass-card" style="padding: 20px; background: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="margin: 0; color: #1a1f36; font-size: 16px;">My Uploaded Materials by Grade</h3>
                        <div class="search-wrapper" style="width: 250px; margin: 0;">
                            <svg class="search-icon" fill="currentColor" viewBox="0 0 24 24" style="left: 10px; width: 18px; height: 18px;"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                            <input type="text" id="teacher-uploaded-search" class="search-input" placeholder="Search by grade or subject..." style="padding-left: 35px; height: 35px;" oninput="filterTeacherUploadedMaterials()">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Target Grade</th>
                                    <th>Subject Name</th>
                                    <th>Topic Name</th>
                                    <th>Material (PDF)</th>
                                    <th>Assignment</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="teacher-uploaded-table-body">
                                <tr>
                                    <td colspan="7" style="text-align: center; color: #697386; padding: 30px;">Loading your materials...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

    <!-- STUDENT SUBMISSION MODAL -->
    <div id="student-submit-modal" class="modal-overlay hidden">
        <div class="modal-container glass-card" style="margin: auto; max-width: 500px;">
            <div class="modal-header">
                <h3>Submit Homework / Assignment</h3>
                <button class="modal-close-btn" onclick="closeStudentSubmitModal()">&times;</button>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <form id="student-submit-form" onsubmit="handleStudentSubmitAssignment(event)">
                    <input type="hidden" id="student-submit-subject-id">
                    <div style="margin-bottom: 12px; font-weight:600; color:#1a1f36;">
                        Subject: <span id="student-submit-subject-name" style="color:#0077be;"></span>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Select Answer Sheet (PDF File)</label>
                        <input type="file" id="student-assignment-pdf" accept="application/pdf" required style="width:100%;">
                    </div>
                    <button type="submit" class="btn-toggle-fee pay" style="width: 100%; height: 42px; background: #0077be; font-size: 15px; font-weight: bold; border: none; border-radius: 8px; color: white; cursor: pointer;">
                        Submit Homework PDF
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div id="grade-submission-modal" class="modal-overlay hidden">
        <div class="modal-container glass-card" style="margin: auto; max-width: 500px;">
            <div class="modal-header">
                <h3>Grade Student Submission</h3>
                <button class="modal-close-btn" onclick="closeGradeSubmissionModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="grade-submission-form" onsubmit="submitGradeSubmission(event)">
                    <input type="hidden" id="grade-submission-id">
                    
                    <div style="margin-bottom: 14px; background: rgba(0,0,0,0.02); padding: 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,0.05);">
                        <div style="font-size: 11px; font-weight: bold; color: #697386; margin-bottom: 4px;">STUDENT</div>
                        <div id="grade-student-name" style="font-size: 13px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">—</div>
                        
                        <div style="font-size: 11px; font-weight: bold; color: #697386; margin-bottom: 4px;">SUBJECT</div>
                        <div id="grade-subject-name" style="font-size: 13px; font-weight: 600; color: #1a1a1a;">—</div>
                    </div>
                    
                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Grade / Marks</label>
                        <input type="text" id="grade-score" class="search-input" placeholder="e.g. A, B+, 85/100" required style="border:1px solid rgba(0,0,0,0.15);">
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Teacher Feedback</label>
                        <textarea id="grade-feedback" class="search-input" rows="4" placeholder="Enter comments or suggestions..." style="border:1px solid rgba(0,0,0,0.15); height:auto; padding:10px; font-family:inherit; resize:vertical;"></textarea>
                    </div>
                    
                    <div style="display:flex; gap:12px; justify-content:flex-end;">
                        <button type="button" class="btn-toggle-fee unpay" onclick="closeGradeSubmissionModal()" 
                            style="padding:10px 20px; font-size:13px; width:auto; background:#6c757d; border:none;">
                            Cancel
                        </button>
                        <button type="submit" class="btn-toggle-fee pay" 
                            style="padding:10px 20px; font-size:13px; width:auto; background:#4CAF50; border:none; color:white; cursor:pointer;">
                            Save Grade
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EXAM RESULT MODAL -->
    <div id="exam-result-modal" class="modal-overlay hidden">
        <div class="modal-container glass-card" style="margin: auto; max-width: 500px;">
            <div class="modal-header">
                <h3 id="exam-result-modal-title">Add Exam Result</h3>
                <button class="modal-close-btn" onclick="closeExamResultModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="exam-result-form" onsubmit="submitExamResult(event)">
                    <input type="hidden" id="exam-result-id">
                    
                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Select Grade (Class)</label>
                        <select id="exam-result-grade" class="search-input" style="border:1px solid rgba(0,0,0,0.15); background:white; height: 40px; padding: 0 10px;" onchange="filterExamResultStudents()">
                            <option value="">All Grades</option>
                            <!-- Dynamically populated -->
                        </select>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Select Student</label>
                        <select id="exam-result-student" class="search-input" required style="border:1px solid rgba(0,0,0,0.15); background:white; height: 40px; padding: 0 10px;">
                            <!-- Dynamically populated -->
                        </select>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Select Subject</label>
                        <select id="exam-result-subject" class="search-input" required style="border:1px solid rgba(0,0,0,0.15); background:white; height: 40px; padding: 0 10px;">
                            <!-- Dynamically populated -->
                        </select>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Exam Name</label>
                        <input type="text" id="exam-result-name" class="search-input" placeholder="e.g. Term Final Exam, Midterm Exam" required style="border:1px solid rgba(0,0,0,0.15);">
                    </div>

                    <div style="display:flex; gap:12px; margin-bottom:16px;">
                        <div style="flex:1;">
                            <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Marks Obtained</label>
                            <input type="number" id="exam-result-marks" class="search-input" min="0" required style="border:1px solid rgba(0,0,0,0.15);">
                        </div>
                        <div style="flex:1;">
                            <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Total Marks</label>
                            <input type="number" id="exam-result-total-marks" class="search-input" min="1" required style="border:1px solid rgba(0,0,0,0.15);">
                        </div>
                    </div>

                    <div style="display:flex; gap:12px; margin-bottom:16px;">
                        <div style="flex:1;">
                            <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Term</label>
                            <select id="exam-result-term" class="search-input" required style="border:1px solid rgba(0,0,0,0.15); background:white; height: 40px; padding: 0 10px;">
                                <option value="Term 1">Term 1</option>
                                <option value="Term 2">Term 2</option>
                                <option value="Term 3">Term 3</option>
                            </select>
                        </div>
                        <div style="flex:1;">
                            <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Academic Year</label>
                            <select id="exam-result-year" class="search-input" required style="border:1px solid rgba(0,0,0,0.15); background:white; height: 40px; padding: 0 10px;">
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                            </select>
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Remarks</label>
                        <textarea id="exam-result-remarks" class="search-input" rows="3" placeholder="Additional notes..." style="border:1px solid rgba(0,0,0,0.15); height:auto; padding:10px; font-family:inherit; resize:vertical;"></textarea>
                    </div>
                    
                    <div style="display:flex; gap:12px; justify-content:flex-end;">
                        <button type="button" class="btn-toggle-fee unpay" onclick="closeExamResultModal()" 
                            style="padding:10px 20px; font-size:13px; width:auto; background:#6c757d; border:none;">
                            Cancel
                        </button>
                        <button type="submit" class="btn-toggle-fee pay" id="exam-result-submit-btn"
                            style="padding:10px 20px; font-size:13px; width:auto; background:#E91E63; border:none; color:white; cursor:pointer;">
                            Save Result
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- CREATE NOTICE MODAL -->
    <div id="notice-modal" class="modal-overlay hidden">
        <div class="modal-container glass-card" style="margin: auto; max-width: 500px;">
            <div class="modal-header">
                <h3 id="notice-modal-title">Create New Notice</h3>
                <button class="modal-close-btn" onclick="closeNoticeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="notice-form" onsubmit="handleCreateNotice(event)">
                    <input type="hidden" id="notice-edit-id" value="">
                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Title</label>
                        <input type="text" id="notice-title" class="search-input" required placeholder="e.g. Term Test Schedule" style="border:1px solid rgba(0,0,0,0.15);">
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Content</label>
                        <textarea id="notice-content" class="search-input" required rows="4" placeholder="Type notice content here..." style="border:1px solid rgba(0,0,0,0.15); height:auto; padding:10px; font-family:inherit; resize:vertical;"></textarea>
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Category</label>
                        <select id="notice-category" class="filter-select" style="width: 100%; border:1px solid rgba(0,0,0,0.15); height:42px;" required>
                            <option value="academic">Academic</option>
                            <option value="finance">Finance</option>
                            <option value="event">Event</option>
                            <option value="general">General</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Target Audience</label>
                        <select id="notice-audience" class="filter-select" style="width: 100%; border:1px solid rgba(0,0,0,0.15); height:42px;" onchange="toggleNoticeRecipientSelect()" required>
                            <option value="all">All</option>
                            <option value="students">Students Only</option>
                            <option value="teachers">Teachers Only</option>
                            <option value="individual">Individual Student/Teacher</option>
                        </select>
                    </div>
                    <div id="notice-recipient-group" style="margin-bottom: 16px; display: none;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Recipient User</label>
                        <select id="notice-recipient" class="filter-select" style="width: 100%; border:1px solid rgba(0,0,0,0.15); height:42px;">
                            <!-- Dynamically populated -->
                        </select>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Expiry Date (Optional)</label>
                        <input type="date" id="notice-expiry" class="search-input" style="border:1px solid rgba(0,0,0,0.15);">
                    </div>
                    <div style="display:flex; gap:12px; justify-content:flex-end;">
                        <button type="button" class="btn-toggle-fee unpay" onclick="closeNoticeModal()" 
                            style="padding:10px 20px; font-size:13px; width:auto; background:#6c757d; border:none;">
                            Cancel
                        </button>
                        <button type="submit" id="notice-submit-btn" class="btn-toggle-fee pay" 
                            style="padding:10px 20px; font-size:13px; width:auto; background:#0077be; border:none;">
                            Publish Notice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- CREATE EVENT MODAL -->
    <div id="event-modal" class="modal-overlay hidden">
        <div class="modal-container glass-card" style="margin: auto; max-width: 550px;">
            <div class="modal-header">
                <h3 id="event-modal-title">Create New School Event</h3>
                <button class="modal-close-btn" onclick="closeEventModal()">&times;</button>
            </div>
            <div class="modal-body" style="max-height: 480px; overflow-y: auto; padding-right: 5px;">
                <form id="event-form" onsubmit="handleCreateEvent(event)">
                    <input type="hidden" id="event-edit-id" value="">
                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Title</label>
                        <input type="text" id="event-title" class="search-input" required placeholder="e.g. Annual Sports Meet" style="border:1px solid rgba(0,0,0,0.15);">
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Description</label>
                        <textarea id="event-description" class="search-input" required rows="3" placeholder="Type event description here..." style="border:1px solid rgba(0,0,0,0.15); height:auto; padding:10px; font-family:inherit; resize:vertical;"></textarea>
                    </div>
                    <div style="display: flex; gap: 12px; margin-bottom: 16px;">
                        <div style="flex: 1;">
                            <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Date</label>
                            <input type="date" id="event-date" class="search-input" required style="border:1px solid rgba(0,0,0,0.15);">
                        </div>
                        <div style="flex: 1;">
                            <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Location</label>
                            <input type="text" id="event-location" class="search-input" required placeholder="e.g. School Playground" style="border:1px solid rgba(0,0,0,0.15);">
                        </div>
                    </div>
                    <div style="display: flex; gap: 12px; margin-bottom: 16px;">
                        <div style="flex: 1;">
                            <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Start Time</label>
                            <input type="time" id="event-start-time" class="search-input" required style="border:1px solid rgba(0,0,0,0.15);">
                        </div>
                        <div style="flex: 1;">
                            <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">End Time</label>
                            <input type="time" id="event-end-time" class="search-input" required style="border:1px solid rgba(0,0,0,0.15);">
                        </div>
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">What to Bring (Optional)</label>
                        <input type="text" id="event-bring" class="search-input" placeholder="e.g. Water bottle, sports kit" style="border:1px solid rgba(0,0,0,0.15);">
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">What to Do / Instructions (Optional)</label>
                        <input type="text" id="event-todo" class="search-input" placeholder="e.g. Report to section lead by 7:30 AM" style="border:1px solid rgba(0,0,0,0.15);">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Learning Goals (Optional)</label>
                        <input type="text" id="event-goals" class="search-input" placeholder="e.g. Team building and physical coordination" style="border:1px solid rgba(0,0,0,0.15);">
                    </div>
                    <div style="display:flex; gap:12px; justify-content:flex-end;">
                        <button type="button" class="btn-toggle-fee unpay" onclick="closeEventModal()" 
                            style="padding:10px 20px; font-size:13px; width:auto; background:#6c757d; border:none;">
                            Cancel
                        </button>
                        <button type="submit" id="event-submit-btn" class="btn-toggle-fee pay" 
                            style="padding:10px 20px; font-size:13px; width:auto; background:#0077be; border:none;">
                            Create Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- UPDATE COMPLAINT RESPONSE MODAL -->
    <div id="complaint-modal" class="modal-overlay hidden">
        <div class="modal-container glass-card" style="margin: auto; max-width: 500px;">
            <div class="modal-header">
                <h3>Reply to Complaint</h3>
                <button class="modal-close-btn" onclick="closeComplaintModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="complaint-form" onsubmit="handleUpdateComplaint(event)">
                    <input type="hidden" id="complaint-id">
                    <div style="margin-bottom: 14px; background: rgba(0,0,0,0.02); padding: 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,0.05);">
                        <div style="font-size: 11px; font-weight: bold; color: #697386; margin-bottom: 4px;">SUBMITTED BY</div>
                        <div id="complaint-user-info" style="font-size: 13px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">—</div>
                        
                        <div style="font-size: 11px; font-weight: bold; color: #697386; margin-bottom: 4px;">CATEGORY</div>
                        <div id="complaint-category-info" style="font-size: 13px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">—</div>
                        
                        <div style="font-size: 11px; font-weight: bold; color: #697386; margin-bottom: 4px;">MESSAGE</div>
                        <div id="complaint-message-info" style="font-size: 13px; color: #333; line-height: 1.4; white-space: pre-wrap;">—</div>
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Status</label>
                        <select id="complaint-status" class="filter-select" style="width: 100%; border:1px solid rgba(0,0,0,0.15); height:42px;" required>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Admin Response / Reply</label>
                        <textarea id="complaint-response" class="search-input" rows="4" placeholder="Type response message here..." style="border:1px solid rgba(0,0,0,0.15); height:auto; padding:10px; font-family:inherit; resize:vertical;"></textarea>
                    </div>
                    <div style="display:flex; gap:12px; justify-content:flex-end;">
                        <button type="button" class="btn-toggle-fee unpay" onclick="closeComplaintModal()" 
                            style="padding:10px 20px; font-size:13px; width:auto; background:#6c757d; border:none;">
                            Cancel
                        </button>
                        <button type="submit" class="btn-toggle-fee pay" 
                            style="padding:10px 20px; font-size:13px; width:auto; background:#4CAF50; border:none;">
                            Update Complaint
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- UPDATE LEAVE STATUS MODAL -->
    <div id="leave-modal" class="modal-overlay hidden">
        <div class="modal-container glass-card" style="margin: auto; max-width: 500px;">
            <div class="modal-header">
                <h3>Respond to Leave Request</h3>
                <button class="modal-close-btn" onclick="closeLeaveModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="leave-response-form" onsubmit="handleUpdateLeave(event)">
                    <input type="hidden" id="leave-request-id">
                    <div style="margin-bottom: 14px; background: rgba(0,0,0,0.02); padding: 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,0.05);">
                        <div style="font-size: 11px; font-weight: bold; color: #697386; margin-bottom: 4px;">REQUESTED BY</div>
                        <div id="leave-teacher-info" style="font-size: 13px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">—</div>
                        
                        <div style="font-size: 11px; font-weight: bold; color: #697386; margin-bottom: 4px;">LEAVE TYPE & PERIOD</div>
                        <div id="leave-period-info" style="font-size: 13px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">—</div>
                        
                        <div style="font-size: 11px; font-weight: bold; color: #697386; margin-bottom: 4px;">REASON</div>
                        <div id="leave-reason-info" style="font-size: 13px; color: #333; line-height: 1.4; white-space: pre-wrap;">—</div>
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Decision</label>
                        <select id="leave-decision-status" class="filter-select" style="width: 100%; border:1px solid rgba(0,0,0,0.15); height:42px;" required>
                            <option value="approved">Approve</option>
                            <option value="rejected">Reject</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Admin Remarks / Response</label>
                        <textarea id="leave-admin-response" class="search-input" rows="4" placeholder="Type response/remarks here..." style="border:1px solid rgba(0,0,0,0.15); height:auto; padding:10px; font-family:inherit; resize:vertical;"></textarea>
                    </div>
                    <div style="display:flex; gap:12px; justify-content:flex-end;">
                        <button type="button" class="btn-toggle-fee unpay" onclick="closeLeaveModal()" 
                            style="padding:10px 20px; font-size:13px; width:auto; background:#6c757d; border:none;">
                            Cancel
                        </button>
                        <button type="submit" class="btn-toggle-fee pay" 
                            style="padding:10px 20px; font-size:13px; width:auto; background:#4CAF50; border:none;">
                            Submit Response
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- FEES MANAGEMENT MODAL -->
    <div id="fees-modal" class="modal-overlay hidden">
        <div class="modal-container glass-card" style="margin: auto;">
            <div class="modal-header">
                <h3>Manage Student Fees</h3>
                <button class="modal-close-btn" onclick="closeFeesModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="student-info-row">
                    <span class="info-label">Student Name:</span>
                    <span class="info-value" id="fees-student-name">Loading...</span>
                </div>
                <div class="fees-list-container" id="fees-list-container">
                    <!-- Dynamic fee rows -->
                </div>
            </div>
        </div>
    </div>

    <!-- SALARY PAYMENT MODAL -->
    <div id="salary-modal" class="modal-overlay hidden">
        <div class="modal-container glass-card" style="margin: auto; max-width: 500px;">
            <div class="modal-header">
                <h3>Pay Teacher Salary</h3>
                <button class="modal-close-btn" onclick="closeSalaryModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="salary-payment-form" onsubmit="submitSalaryPayment(event)">
                    <input type="hidden" id="salary-teacher-id">
                    <input type="hidden" id="salary-month">
                    <input type="hidden" id="salary-year">

                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Teacher</label>
                        <input type="text" id="salary-teacher-name" class="search-input" readonly 
                            style="background:rgba(0,0,0,0.03); cursor:not-allowed; border:1px solid rgba(0,0,0,0.1);">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">For Period</label>
                        <input type="text" id="salary-period" class="search-input" readonly 
                            style="background:rgba(0,0,0,0.03); cursor:not-allowed; border:1px solid rgba(0,0,0,0.1);">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Payment Amount (LKR)</label>
                        <input type="number" step="0.01" id="salary-amount" class="search-input" required 
                            style="border:1px solid rgba(0,0,0,0.15);">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Payment Method</label>
                        <select id="salary-method" class="filter-select" style="width:100%; border:1px solid rgba(0,0,0,0.15); height:42px;">
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Notes / Remarks</label>
                        <textarea id="salary-notes" class="search-input" rows="3" placeholder="Optional notes e.g. bank slip reference..."
                            style="border:1px solid rgba(0,0,0,0.15); height:auto; padding:10px; font-family:inherit; resize:vertical;"></textarea>
                    </div>

                    <div style="display:flex; gap:12px; justify-content:flex-end;">
                        <button type="button" class="btn-toggle-fee unpay" onclick="closeSalaryModal()" 
                            style="padding:10px 20px; font-size:13px; width:auto; background:#6c757d; border:none;">
                            Cancel
                        </button>
                        <button type="submit" class="btn-toggle-fee pay" 
                            style="padding:10px 20px; font-size:13px; width:auto; background:#0077be; border:none;">
                            Confirm Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- ASSIGN TEACHER TO GRADE MODAL -->
    <div id="assign-teacher-modal" class="modal-overlay hidden">
        <div class="modal-container glass-card" style="margin: auto; max-width: 500px;">
            <div class="modal-header">
                <h3>Assign Class Teacher to Grade</h3>
                <button class="modal-close-btn" onclick="closeAssignTeacherModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="assign-teacher-form" onsubmit="submitAssignTeacher(event)">
                    <input type="hidden" id="assign-grade-id">
                    
                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Grade</label>
                        <input type="text" id="assign-grade-name" class="search-input" readonly 
                            style="background:rgba(0,0,0,0.03); cursor:not-allowed; border:1px solid rgba(0,0,0,0.1);">
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Select Class Teacher</label>
                        <select id="assign-teacher-select" class="search-input" style="border:1px solid rgba(0,0,0,0.15); background:white; height: 42px; padding: 0 10px;">
                            <!-- Populated dynamically with teacher options -->
                        </select>
                    </div>
                    
                    <div style="display:flex; gap:12px; justify-content:flex-end;">
                        <button type="button" class="btn-toggle-fee unpay" onclick="closeAssignTeacherModal()" 
                            style="padding:10px 20px; font-size:13px; width:auto; background:#6c757d; border:none; color:white;">
                            Cancel
                        </button>
                        <button type="submit" class="btn-toggle-fee pay" 
                            style="padding:10px 20px; font-size:13px; width:auto; background:#0077be; border:none; color:white; cursor:pointer;">
                            Assign Teacher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- QR CODE DISPLAY MODAL -->
    <div id="qr-modal" class="modal-overlay hidden">
        <div class="modal-container glass-card" style="margin: auto; max-width: 400px; text-align: center;">
            <div class="modal-header">
                <h3>User Attendance QR Code</h3>
                <button class="modal-close-btn" onclick="closeQrModal()">&times;</button>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <div id="qr-user-details" style="margin-bottom: 20px;">
                    <div id="qr-user-name" style="font-size: 18px; font-weight: 700; color: #1a1a1a;">—</div>
                    <div id="qr-user-role" style="font-size: 12px; font-weight: 600; color: #697386; margin-top: 4px; text-transform: uppercase;">—</div>
                </div>
                <div style="background: white; padding: 15px; border-radius: 12px; display: inline-block; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 20px;">
                    <img id="qr-image" src="" alt="QR Code" style="width: 200px; height: 200px; display: block;">
                </div>
                <p style="font-size: 12px; color: #697386; margin-bottom: 25px;">Scan this QR code at the school gate to record attendance.</p>
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <button type="button" class="btn-toggle-fee unpay" onclick="closeQrModal()" 
                        style="padding:10px 20px; font-size:13px; width:auto; background:#6c757d; border:none; margin: 0;">
                        Close
                    </button>
                    <button type="button" class="btn-toggle-fee pay" onclick="downloadQrCode()" 
                        style="padding:10px 20px; font-size:13px; width:auto; background:#0077be; border:none; margin: 0;">
                        Download QR
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- GALLERY EVENT MODAL -->
    <div id="gallery-modal" class="modal-overlay hidden">
        <div class="modal-container glass-card" style="margin: auto; max-width: 600px; padding: 28px;">
            <div class="modal-header">
                <h3 style="font-size:18px; font-weight:700; color:#1a1f36;">Create New Gallery Event Album</h3>
                <button class="modal-close-btn" onclick="closeGalleryModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="gallery-form" onsubmit="handleCreateGallery(event)">
                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:13px; font-weight:700; color:#1a1f36; margin-bottom:6px;">Event Name / Title <span style="color:#ff4d4f;">*</span></label>
                        <input type="text" id="gallery-event-name" class="search-input" placeholder="e.g. Annual Sports Day 2026, Science Fair, Music Concert" required style="border:1px solid rgba(0,0,0,0.15); width:100%; height:42px;">
                    </div>

                    <div style="display:flex; gap:16px; margin-bottom:16px;">
                        <div style="flex:1;">
                            <label style="display:block; font-size:13px; font-weight:700; color:#1a1f36; margin-bottom:6px;">Event Date</label>
                            <input type="date" id="gallery-event-date" class="search-input" style="border:1px solid rgba(0,0,0,0.15); width:100%; height:42px; background:white;">
                        </div>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:13px; font-weight:700; color:#1a1f36; margin-bottom:6px;">Event Description (Optional)</label>
                        <textarea id="gallery-description" class="search-input" rows="3" placeholder="Brief details or highlights of this event..." style="border:1px solid rgba(0,0,0,0.15); width:100%; height:auto; padding:10px; font-family:inherit; resize:vertical;"></textarea>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-size:13px; font-weight:700; color:#1a1f36; margin-bottom:6px;">Upload Event Photos (Multiple Images) <span style="color:#ff4d4f;">*</span></label>
                        <div style="border: 2px dashed rgba(0,119,190,0.3); border-radius: 12px; padding: 24px; text-align: center; background: rgba(0,119,190,0.02); cursor: pointer;" onclick="document.getElementById('gallery-images-input').click()">
                            <svg width="40" height="40" fill="#0077be" viewBox="0 0 24 24" style="margin-bottom: 8px;"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54-1.96-2.36L6.5 17h11l-3.54-4.71z"/></svg>
                            <div style="font-weight: 700; color: #1a1f36; font-size: 14px;">Click here to select multiple images</div>
                            <div style="font-size: 12px; color: #697386; margin-top: 4px;">Supports JPG, PNG, WEBP, GIF (Select any number of photos)</div>
                            <input type="file" id="gallery-images-input" multiple accept="image/*" required style="display:none;" onchange="handleGalleryImagesPreview(event)">
                        </div>
                        
                        <!-- Image Previews Grid -->
                        <div id="gallery-images-preview-container" style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 14px; max-height: 180px; overflow-y: auto; padding: 4px;"></div>
                    </div>

                    <div style="display:flex; gap:12px; justify-content:flex-end; margin-top: 24px;">
                        <button type="button" class="btn" onclick="closeGalleryModal()" style="background:#6c757d; color:white; border:none; padding:10px 20px; font-weight:600; border-radius:8px; cursor:pointer;">
                            Cancel
                        </button>
                        <button type="submit" id="gallery-submit-btn" class="btn" style="background:#0077be; color:white; border:none; padding:10px 24px; font-weight:700; border-radius:8px; cursor:pointer; display:flex; align-items:center; gap:6px;">
                            <span>Upload Gallery Album</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ADD MORE IMAGES TO ALBUM MODAL -->
    <div id="add-more-images-modal" class="modal-overlay hidden">
        <div class="modal-container glass-card" style="margin: auto; max-width: 500px; padding: 24px;">
            <div class="modal-header">
                <h3 style="font-size:16px; font-weight:700; color:#1a1f36;">Add More Photos to Album</h3>
                <button class="modal-close-btn" onclick="closeAddMoreImagesModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="add-more-images-form" onsubmit="handleUploadMoreImages(event)">
                    <input type="hidden" id="add-more-images-gallery-id">
                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:13px; font-weight:700; color:#1a1f36; margin-bottom:6px;">Select New Photos</label>
                        <input type="file" id="add-more-images-input" multiple accept="image/*" required class="search-input" style="width:100%; border:1px solid rgba(0,0,0,0.15);">
                    </div>
                    <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:20px;">
                        <button type="button" class="btn" onclick="closeAddMoreImagesModal()" style="background:#6c757d; color:white; border:none; padding:8px 16px; border-radius:8px; cursor:pointer;">Cancel</button>
                        <button type="submit" id="add-more-submit-btn" class="btn" style="background:#4CAF50; color:white; border:none; padding:8px 20px; font-weight:700; border-radius:8px; cursor:pointer;">Upload Photos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- LIGHTBOX PHOTO VIEWER MODAL -->
    <div id="gallery-lightbox-modal" class="modal-overlay hidden" style="background: rgba(0,0,0,0.9); z-index:9999;">
        <div style="position: relative; width: 90vw; max-width: 1000px; max-height: 90vh; margin: auto; padding: 20px;">
            <button onclick="closeGalleryLightbox()" style="position: absolute; top: 10px; right: 15px; background: rgba(255,255,255,0.2); border: none; color: white; font-size: 24px; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
            <h3 id="lightbox-title" style="color: white; margin: 0 0 16px 0; font-size: 20px; font-weight: 700; text-align: left;">Album Title</h3>
            <div id="lightbox-images-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 14px; max-height: 75vh; overflow-y: auto; padding-right: 8px;">
                <!-- Full photos loaded dynamically -->
            </div>
        </div>
    </div>

    <!-- USER CREATE / EDIT MODAL -->
    <div id="user-modal" class="modal-overlay hidden">
        <div class="modal-container glass-card" style="margin: auto; max-width: 550px; max-height: 90vh; overflow-y: auto;">
            <div class="modal-header">
                <h3 id="user-modal-title">Register User</h3>
                <button class="modal-close-btn" onclick="closeUserModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="user-form" onsubmit="submitUserForm(event)">
                    <input type="hidden" id="user-id">
                    <input type="hidden" id="user-role-val">

                    <!-- Basic Fields -->
                    <div style="margin-bottom: 14px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Full Name</label>
                        <input type="text" id="user-name" class="search-input" required style="border:1px solid rgba(0,0,0,0.15); background:white; height: 40px; padding: 0 10px;">
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Email Address</label>
                        <input type="email" id="user-email" class="search-input" required style="border:1px solid rgba(0,0,0,0.15); background:white; height: 40px; padding: 0 10px;">
                    </div>

                    <!-- Password field (shown only in create mode) -->
                    <div id="user-password-container" style="margin-bottom: 14px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Password</label>
                        <input type="password" id="user-password" class="search-input" style="border:1px solid rgba(0,0,0,0.15); background:white; height: 40px; padding: 0 10px;">
                    </div>

                    <div style="margin-bottom: 14px; display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Phone Number</label>
                            <input type="text" id="user-phone" class="search-input" style="border:1px solid rgba(0,0,0,0.15); background:white; height: 40px; padding: 0 10px;">
                        </div>
                        <div>
                            <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Date of Birth</label>
                            <input type="date" id="user-dob" class="search-input" style="border:1px solid rgba(0,0,0,0.15); background:white; height: 40px; padding: 0 10px;">
                        </div>
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Residential Address</label>
                        <input type="text" id="user-address" class="search-input" style="border:1px solid rgba(0,0,0,0.15); background:white; height: 40px; padding: 0 10px;">
                    </div>

                    <!-- Student Specific Fields -->
                    <div id="student-fields-section" class="hidden" style="margin-top: 20px; border-top: 1px dashed rgba(0,0,0,0.1); padding-top: 15px;">
                        <h4 style="margin-bottom: 12px; color: #1a1a1a;">Student Details</h4>
                        <div style="margin-bottom: 14px;">
                            <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Grade</label>
                            <select id="student-grade" class="search-input" style="border:1px solid rgba(0,0,0,0.15); background:white; height: 40px; padding: 0 10px; width: 100%;">
                                <option value="1">Grade 1</option>
                                <option value="2">Grade 2</option>
                                <option value="3">Grade 3</option>
                                <option value="4">Grade 4</option>
                                <option value="5">Grade 5</option>
                                <option value="6">Grade 6</option>
                                <option value="7">Grade 7</option>
                                <option value="8">Grade 8</option>
                                <option value="9">Grade 9</option>
                                <option value="10">Grade 10</option>
                                <option value="11">Grade 11</option>
                                <option value="12">Grade 12</option>
                            </select>
                        </div>

                        <h4 style="margin-bottom: 12px; color: #1a1a1a; margin-top: 15px;">Parent / Guardian Contact Details</h4>
                        <div style="margin-bottom: 14px;">
                            <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Parent Full Name</label>
                            <input type="text" id="student-parent-name" class="search-input" style="border:1px solid rgba(0,0,0,0.15); background:white; height: 40px; padding: 0 10px;">
                        </div>
                        <div style="margin-bottom: 14px; display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <div>
                                <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Parent Email</label>
                                <input type="email" id="student-parent-email" class="search-input" style="border:1px solid rgba(0,0,0,0.15); background:white; height: 40px; padding: 0 10px;">
                            </div>
                            <div>
                                <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Parent Phone</label>
                                <input type="text" id="student-parent-phone" class="search-input" style="border:1px solid rgba(0,0,0,0.15); background:white; height: 40px; padding: 0 10px;">
                            </div>
                        </div>
                    </div>

                    <!-- Teacher Specific Fields -->
                    <div id="teacher-fields-section" class="hidden" style="margin-top: 20px; border-top: 1px dashed rgba(0,0,0,0.1); padding-top: 15px;">
                        <h4 style="margin-bottom: 12px; color: #1a1a1a;">Teacher Profile</h4>
                        <div style="margin-bottom: 14px;">
                            <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Subject Specialization</label>
                            <input type="text" id="teacher-subject" class="search-input" placeholder="e.g. Mathematics, English" style="border:1px solid rgba(0,0,0,0.15); background:white; height: 40px; padding: 0 10px;">
                        </div>
                        <div style="margin-bottom: 14px;">
                            <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Qualifications</label>
                            <input type="text" id="teacher-qualification" class="search-input" placeholder="e.g. B.Sc in Education, Diploma" style="border:1px solid rgba(0,0,0,0.15); background:white; height: 40px; padding: 0 10px;">
                        </div>
                    </div>

                    <div style="display:flex; gap:12px; justify-content:flex-end; margin-top: 25px;">
                        <button type="button" class="btn-toggle-fee unpay" onclick="closeUserModal()" 
                            style="padding:10px 20px; font-size:13px; width:auto; background:#6c757d; border:none; margin:0;">
                            Cancel
                        </button>
                        <button type="submit" class="btn-toggle-fee pay" 
                            style="padding:10px 20px; font-size:13px; width:auto; background:#4CAF50; border:none; color:white; cursor:pointer; margin:0;">
                            Save User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- VIEW STUDENT DETAILS MODAL -->
    <div id="view-student-modal" class="modal-overlay hidden" onclick="if(event.target === this) closeViewStudentModal()">
        <div class="modal-container glass-card" style="margin: auto; max-width: 600px; padding: 0; overflow: hidden; border-radius: 16px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #0077be 0%, #00a8ff 100%); color: white; padding: 18px 24px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        🎓
                    </div>
                    <div>
                        <h3 style="margin: 0; color: white; font-size: 17px;">Student Profile &amp; Details</h3>
                        <p style="margin: 2px 0 0 0; font-size: 12px; color: rgba(255,255,255,0.85);" id="view-student-header-sub">—</p>
                    </div>
                </div>
                <button class="modal-close-btn" onclick="closeViewStudentModal()" style="color: white; font-size: 24px;">&times;</button>
            </div>
            
            <div class="modal-body" style="padding: 24px; max-height: 75vh; overflow-y: auto;">
                <!-- Student Details Section -->
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px; border-bottom: 2px solid #e8f4fd; padding-bottom: 6px;">
                        <span style="font-size: 16px;">👤</span>
                        <h4 style="margin: 0; color: #0077be; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Student Information</h4>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div style="background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Full Name</span>
                            <div id="v-student-name" style="font-size: 14px; font-weight: 600; color: #1e293b; margin-top: 2px;">—</div>
                        </div>
                        <div style="background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Student ID</span>
                            <div id="v-student-id" style="font-size: 14px; font-weight: 700; color: #0077be; margin-top: 2px;">—</div>
                        </div>
                        <div style="background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Grade / Class</span>
                            <div id="v-student-grade" style="font-size: 14px; font-weight: 600; color: #1e293b; margin-top: 2px;">—</div>
                        </div>
                        <div style="background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Section</span>
                            <div id="v-student-section" style="font-size: 14px; font-weight: 600; color: #1e293b; margin-top: 2px;">—</div>
                        </div>
                        <div style="background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Email</span>
                            <div id="v-student-email" style="font-size: 13px; font-weight: 500; color: #1e293b; margin-top: 2px; word-break: break-all;">—</div>
                        </div>
                        <div style="background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Phone Number</span>
                            <div id="v-student-phone" style="font-size: 13px; font-weight: 500; color: #1e293b; margin-top: 2px;">—</div>
                        </div>
                        <div style="background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Date of Birth</span>
                            <div id="v-student-dob" style="font-size: 13px; font-weight: 500; color: #1e293b; margin-top: 2px;">—</div>
                        </div>
                        <div style="background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Address</span>
                            <div id="v-student-address" style="font-size: 13px; font-weight: 500; color: #1e293b; margin-top: 2px;">—</div>
                        </div>
                    </div>
                </div>

                <!-- Parent / Guardian Details Section -->
                <div>
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px; border-bottom: 2px solid #e8f4fd; padding-bottom: 6px;">
                        <span style="font-size: 16px;">👨‍👩‍👧</span>
                        <h4 style="margin: 0; color: #0077be; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Parent / Guardian Information</h4>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div style="grid-column: 1 / -1; background: #f0fdf4; padding: 12px 14px; border-radius: 8px; border: 1px solid #bbf7d0;">
                            <span style="font-size: 11px; font-weight: 700; color: #166534; text-transform: uppercase;">Parent / Guardian Name</span>
                            <div id="v-parent-name" style="font-size: 15px; font-weight: 700; color: #14532d; margin-top: 2px;">—</div>
                        </div>
                        <div style="background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Parent Email</span>
                            <div id="v-parent-email" style="font-size: 13px; font-weight: 500; color: #1e293b; margin-top: 2px; word-break: break-all;">—</div>
                        </div>
                        <div style="background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Parent Phone</span>
                            <div id="v-parent-phone" style="font-size: 13px; font-weight: 500; color: #1e293b; margin-top: 2px;">—</div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 24px;">
                    <button type="button" class="btn-toggle-fee pay" onclick="closeViewStudentModal()" style="padding: 10px 24px; font-weight: 600; font-size: 13px; background: #64748b; border: none; border-radius: 8px; color: white; cursor: pointer;">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- VIEW TEACHER DETAILS MODAL -->
    <div id="view-teacher-modal" class="modal-overlay hidden" onclick="if(event.target === this) closeViewTeacherModal()">
        <div class="modal-container glass-card" style="margin: auto; max-width: 600px; padding: 0; overflow: hidden; border-radius: 16px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #9c27b0 0%, #ba68c8 100%); color: white; padding: 18px 24px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        👨‍🏫
                    </div>
                    <div>
                        <h3 style="margin: 0; color: white; font-size: 17px;">Teacher Profile &amp; Details</h3>
                        <p style="margin: 2px 0 0 0; font-size: 12px; color: rgba(255,255,255,0.85);" id="view-teacher-header-sub">—</p>
                    </div>
                </div>
                <button class="modal-close-btn" onclick="closeViewTeacherModal()" style="color: white; font-size: 24px;">&times;</button>
            </div>
            
            <div class="modal-body" style="padding: 24px; max-height: 75vh; overflow-y: auto;">
                <!-- Teacher Details Section -->
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px; border-bottom: 2px solid #f3e5f5; padding-bottom: 6px;">
                        <span style="font-size: 16px;">👤</span>
                        <h4 style="margin: 0; color: #9c27b0; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Teacher Information</h4>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div style="background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Full Name</span>
                            <div id="v-teacher-name" style="font-size: 14px; font-weight: 600; color: #1e293b; margin-top: 2px;">—</div>
                        </div>
                        <div style="background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Employee ID</span>
                            <div id="v-teacher-id" style="font-size: 14px; font-weight: 700; color: #9c27b0; margin-top: 2px;">—</div>
                        </div>
                        <div style="background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Subject Specialization</span>
                            <div id="v-teacher-subject" style="font-size: 14px; font-weight: 600; color: #1e293b; margin-top: 2px;">—</div>
                        </div>
                        <div style="background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Qualification</span>
                            <div id="v-teacher-qualification" style="font-size: 14px; font-weight: 600; color: #1e293b; margin-top: 2px;">—</div>
                        </div>
                        <div style="background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Email</span>
                            <div id="v-teacher-email" style="font-size: 13px; font-weight: 500; color: #1e293b; margin-top: 2px; word-break: break-all;">—</div>
                        </div>
                        <div style="background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Phone Number</span>
                            <div id="v-teacher-phone" style="font-size: 13px; font-weight: 500; color: #1e293b; margin-top: 2px;">—</div>
                        </div>
                        <div style="background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Date of Birth</span>
                            <div id="v-teacher-dob" style="font-size: 13px; font-weight: 500; color: #1e293b; margin-top: 2px;">—</div>
                        </div>
                        <div style="background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Basic Salary</span>
                            <div id="v-teacher-salary" style="font-size: 13px; font-weight: 700; color: #0077be; margin-top: 2px;">—</div>
                        </div>
                        <div style="grid-column: 1 / -1; background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Residential Address</span>
                            <div id="v-teacher-address" style="font-size: 13px; font-weight: 500; color: #1e293b; margin-top: 2px;">—</div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 24px;">
                    <button type="button" class="btn-toggle-fee pay" onclick="closeViewTeacherModal()" style="padding: 10px 24px; font-weight: 600; font-size: 13px; background: #64748b; border: none; border-radius: 8px; color: white; cursor: pointer;">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- CHANGE PASSWORD MODAL -->
    <div id="change-password-modal" class="modal-overlay hidden">
        <div class="modal-container glass-card" style="margin: auto; max-width: 450px;">
            <div class="modal-header">
                <h3>Change User Password</h3>
                <button class="modal-close-btn" onclick="closeChangePasswordModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="change-password-form" onsubmit="submitChangePassword(event)">
                    <input type="hidden" id="change-password-user-id">
                    
                    <div style="margin-bottom: 14px; background: rgba(0,0,0,0.02); padding: 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,0.05);">
                        <div style="font-size: 11px; font-weight: bold; color: #697386; margin-bottom: 4px;">USER</div>
                        <div id="change-password-user-name" style="font-size: 14px; font-weight: 600; color: #1a1a1a;">—</div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">New Password</label>
                        <input type="password" id="new-password" class="search-input" required minlength="6" placeholder="Enter at least 6 characters" style="border:1px solid rgba(0,0,0,0.15); background:white; height: 40px; padding: 0 10px;">
                    </div>

                    <div style="display:flex; gap:12px; justify-content:flex-end;">
                        <button type="button" class="btn-toggle-fee unpay" onclick="closeChangePasswordModal()" 
                            style="padding:10px 20px; font-size:13px; width:auto; background:#6c757d; border:none; margin:0;">
                            Cancel
                        </button>
                        <button type="submit" class="btn-toggle-fee pay" 
                            style="padding:10px 20px; font-size:13px; width:auto; background:#ff9800; border:none; color:white; cursor:pointer; margin:0;">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- EDIT SUBJECT MODAL -->
    <div id="edit-subject-modal" class="modal-overlay hidden">
        <div class="modal-container glass-card" style="margin: auto; max-width: 450px;">
            <div class="modal-header">
                <h3>Edit Subject</h3>
                <button class="modal-close-btn" onclick="closeEditSubjectModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="edit-subject-form" onsubmit="submitEditSubject(event)">
                    <input type="hidden" id="edit-subject-id">
                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Subject Name</label>
                        <input type="text" id="edit-subject-name" class="search-input" required style="border:1px solid rgba(0,0,0,0.15); background:white; height:40px; padding:0 10px;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; font-size:12px; font-weight:600; color:#697386; margin-bottom:6px;">Target Grade</label>
                        <select id="edit-subject-grade" class="search-input" required style="border:1px solid rgba(0,0,0,0.15); background:white; height:40px; padding:0 10px;">
                            <option value="">Select a Grade</option>
                            <option value="Grade 1">Grade 1</option>
                            <option value="Grade 2">Grade 2</option>
                            <option value="Grade 3">Grade 3</option>
                            <option value="Grade 4">Grade 4</option>
                            <option value="Grade 5">Grade 5</option>
                            <option value="Grade 6">Grade 6</option>
                            <option value="Grade 7">Grade 7</option>
                            <option value="Grade 8">Grade 8</option>
                            <option value="Grade 9">Grade 9</option>
                            <option value="Grade 10">Grade 10</option>
                            <option value="Grade 11">Grade 11</option>
                            <option value="Grade 12">Grade 12</option>
                        </select>
                    </div>
                    <div style="display:flex; gap:12px; justify-content:flex-end;">
                        <button type="button" class="btn-toggle-fee unpay" onclick="closeEditSubjectModal()"
                            style="padding:10px 20px; font-size:13px; width:auto; background:#6c757d; border:none; margin:0;">
                            Cancel
                        </button>
                        <button type="submit" class="btn-toggle-fee pay"
                            style="padding:10px 20px; font-size:13px; width:auto; background:#0077be; border:none; color:white; cursor:pointer; margin:0;">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Component -->
    <div id="toast" class="toast">
        <span id="toast-text">Action completed successfully</span>
    </div>

    <script>
        // API Base URL
        const API_BASE = '/api';

        // Helper function for storage file URLs
        function getFileUrl(filePath) {
            if (!filePath) return '';
            if (filePath.startsWith('http://') || filePath.startsWith('https://')) {
                return filePath;
            }
            let clean = filePath.replace(/^(\/?storage)+/i, 'storage').replace(/^\/+/, '');
            if (!clean.startsWith('storage/')) {
                clean = 'storage/' + clean;
            }
            return '/' + clean;
        }
        
        // State
        let currentView = 'dashboard';
        let usersData = [];
        let teachersData = [];
        let attendanceData = [];
        let teacherAttendanceData = [];
        let teacherOwnAttendanceData = [];
        let timetableRecords = [];
        let timetableType = 'class'; // 'class' or 'teacher'

        const slotsMetadata = {
            't_8_00_8_30': '08:00 AM – 08:30 AM',
            't_8_30_9_00': '08:30 AM – 09:00 AM',
            't_9_00_9_30': '09:00 AM – 09:30 AM',
            't_9_30_10_00': '09:30 AM – 10:00 AM',
            't_10_00_10_30': '10:00 AM – 10:30 AM',
            't_10_30_11_00': '10:30 AM – 11:00 AM',
            't_11_00_11_30': '11:00 AM – 11:30 AM',
            't_11_30_12_00': '11:30 AM – 12:00 PM',
            't_12_00_12_30': '12:00 PM – 12:30 PM',
            't_12_30_1_00': '12:30 PM – 01:00 PM',
            't_1_00_1_30': '01:00 PM – 01:30 PM'
        };

        // DOM Elements
        const loginContainer = document.getElementById('login-container');
        const portalContainer = document.getElementById('portal-container');
        const loginForm = document.getElementById('login-form');
        const loginError = document.getElementById('login-error');
        const btnLogout = document.getElementById('btn-logout');

        // Initial setup
        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('admin_token');
            if (token) {
                showPortal();
            } else {
                showLogin();
            }
            
            // Build timetable inputs
            buildTimetableFormFields();
        });

        // 1. Auth handlers
        async function handleLoginSubmit(e) {
            if (e) {
                if (typeof e.preventDefault === 'function') e.preventDefault();
                if (typeof e.stopPropagation === 'function') e.stopPropagation();
            }
            
            const loginError = document.getElementById('login-error');
            if (loginError) {
                loginError.classList.add('hidden');
                loginError.innerText = '';
            }
            
            const emailEl = document.getElementById('login-email');
            const passwordEl = document.getElementById('login-password');
            if (!emailEl || !passwordEl) return false;

            const email = emailEl.value.trim();
            const password = passwordEl.value;

            if (!email || !password) {
                showError('Please enter both email and password.');
                return false;
            }

            try {
                const res = await fetch(`${API_BASE}/login`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, password })
                });
                
                const data = await res.json();
                
                if (res.ok && data.token) {
                    const role = (data.role || (data.user ? data.user.role : '') || '').toLowerCase();
                    if (role !== 'admin' && role !== 'teacher' && role !== 'student') {
                        showError('Access denied: Unauthorized role.');
                        return false;
                    }
                    
                    localStorage.setItem('admin_token', data.token);
                    localStorage.setItem('admin_user', JSON.stringify(data.user));
                    localStorage.setItem('user_role', role);
                    window.currentTeacherClassGrades = undefined;
                    window.currentTeacherGrades = undefined;
                    window.currentTeacherSubjects = undefined;
                    window.currentTeacherGradeSubjectsMap = undefined;
                    
                    showToast('Successfully logged in!', 'success');
                    showPortal();
                } else {
                    showError(data.error || data.message || 'Invalid email or password.');
                }
            } catch (err) {
                console.error("Login error:", err);
                showError('Network connection issue. Please make sure the server is serving.');
            }
            return false;
        }

        if (loginForm) {
            loginForm.addEventListener('submit', handleLoginSubmit);
        }

        btnLogout.addEventListener('click', () => {
            localStorage.removeItem('admin_token');
            localStorage.removeItem('admin_user');
            localStorage.removeItem('user_role');
            window.currentTeacherClassGrades = undefined;
            window.currentTeacherGrades = undefined;
            window.currentTeacherSubjects = undefined;
            window.currentTeacherGradeSubjectsMap = undefined;
            if (chatMessagesPollingInterval) {
                clearInterval(chatMessagesPollingInterval);
                chatMessagesPollingInterval = null;
            }
            showLogin();
            showToast('Logged out successfully', 'success');
        });

        function showLogin() {
            loginContainer.classList.remove('hidden');
            portalContainer.classList.add('hidden');
        }

        function showPortal() {
            try {
                loginContainer.classList.add('hidden');
                portalContainer.classList.remove('hidden');
                
                // Set admin credentials
                const admin = JSON.parse(localStorage.getItem('admin_user') || '{}');
                const role = ((admin && admin.role) || localStorage.getItem('user_role') || 'admin').toLowerCase();
                
                const nameEl = document.getElementById('admin-name');
                if (nameEl) nameEl.innerText = admin.name || 'User';
                
                const roleEl = document.getElementById('admin-role');
                if (roleEl) roleEl.innerText = role.toUpperCase();
                
                const avatarEl = document.getElementById('admin-avatar');
                if (avatarEl) avatarEl.innerText = (admin.name || 'U').substring(0, 1).toUpperCase();
                
                // Show/Hide menu items based on role
                document.querySelectorAll('.menu-list > li[data-roles]').forEach(li => {
                    const roles = li.getAttribute('data-roles').split(',');
                    if (roles.includes(role)) {
                        li.classList.remove('hidden');
                    } else {
                        li.classList.add('hidden');
                    }
                });
                
                const metric1 = document.querySelector('#view-dashboard .metric-card:nth-child(1) .metric-label');
                const metric2 = document.querySelector('#view-dashboard .metric-card:nth-child(2) .metric-label');
                const metric3 = document.querySelector('#view-dashboard .metric-card:nth-child(3) .metric-label');
                const shortcutGrid = document.querySelector('#view-dashboard .shortcut-grid');

                // Adjust metrics and layout on dashboard based on role
                if (role === 'teacher') {
                    if (metric1) metric1.innerText = 'My Subjects / Assignments';
                    if (metric2) metric2.innerText = 'Student Assignment Submissions';
                    if (metric3) metric3.innerText = 'Timetable Schedule';
                    
                    if (shortcutGrid) {
                        shortcutGrid.innerHTML = `
                            <div class="shortcut-card glass-card" onclick="switchView('teacher-timetable')">
                                <h3 class="shortcut-title">
                                    <span style="color: #0077be;">✦</span> View My Timetable
                                </h3>
                                <p class="shortcut-desc">Inspect your daily schedules, class assignments, and teaching hours for the current week.</p>
                            </div>
                            <div class="shortcut-card glass-card" onclick="switchView('teacher-materials')">
                                <h3 class="shortcut-title">
                                    <span style="color: #9c27b0;">✦</span> Upload Class Materials
                                </h3>
                                <p class="shortcut-desc">Create subject tasks, distribute instructions, attach assignment sheets or PDFs, and set submission deadlines.</p>
                            </div>
                            <div class="shortcut-card glass-card" onclick="switchView('teacher-submissions')">
                                <h3 class="shortcut-title">
                                    <span style="color: #ff9800;">✦</span> Grade Submissions
                                </h3>
                                <p class="shortcut-desc">Review homework and papers submitted by students, read the answer PDFs, and input scores and feedback.</p>
                            </div>
                        `;
                    }
                    setupTeacherDashboard(admin);
                } else if (role === 'student') {
                    if (metric1) metric1.innerText = 'My Grade & Class';
                    if (metric2) metric2.innerText = 'Class Assignments';
                    if (metric3) metric3.innerText = 'Timetable Schedule';
                    
                    if (shortcutGrid) {
                        shortcutGrid.innerHTML = `
                            <div class="shortcut-card glass-card" onclick="switchView('teacher-uploaded-materials')">
                                <h3 class="shortcut-title">
                                    <span style="color: #0077be;">✦</span> Class Materials & Assignments
                                </h3>
                                <p class="shortcut-desc">View subject materials, download assignment sheets, and submit your completed homework PDFs.</p>
                            </div>
                            <div class="shortcut-card glass-card" onclick="switchView('teacher-timetable')">
                                <h3 class="shortcut-title">
                                    <span style="color: #9c27b0;">✦</span> View Timetable
                                </h3>
                                <p class="shortcut-desc">Check your weekly class schedules and subjects.</p>
                            </div>
                        `;
                    }
                } else {
                    if (metric1) metric1.innerText = 'Total Users';
                    if (metric2) metric2.innerText = 'Academic Staff';
                    if (metric3) metric3.innerText = 'Total Students';
                    
                    if (shortcutGrid) {
                        shortcutGrid.innerHTML = `
                            <div class="shortcut-card glass-card" onclick="switchView('users')">
                                <h3 class="shortcut-title">
                                    <span style="color: #0077be;">✦</span> Inspect Users Directory
                                </h3>
                                <p class="shortcut-desc">Manage system accounts, update roles, details, and search across students, teachers, and administrators.</p>
                            </div>
                            <div class="shortcut-card glass-card" onclick="switchView('teachers')">
                                <h3 class="shortcut-title">
                                    <span style="color: #9c27b0;">✦</span> View Teacher Specialties
                                </h3>
                                <p class="shortcut-desc">Check teacher qualifications, subjects taught, specialization fields, and school-assigned employee IDs.</p>
                            </div>
                            <div class="shortcut-card glass-card" onclick="switchView('timetable')">
                                <h3 class="shortcut-title">
                                    <span style="color: #ff9800;">✦</span> Manage Class Timetables
                                </h3>
                                <p class="shortcut-desc">Add and modify 30-minute time slot entries for any weekday and grade level, updating schedules instantly.</p>
                            </div>
                        `;
                    }
                    fetchDashboardMetrics();
                }
                
                // Reset to dashboard
                switchView('dashboard');
            } catch (err) {
                console.error("showPortal error:", err);
            }
        }

        function showError(msg) {
            loginError.innerText = msg;
            loginError.classList.remove('hidden');
        }

        // 2. View Switcher
        function switchView(viewName) {
            currentView = viewName;
            
            // Clean up chat polling if leaving messages view
            if (chatMessagesPollingInterval && viewName !== 'messages') {
                clearInterval(chatMessagesPollingInterval);
                chatMessagesPollingInterval = null;
            }

            // Hide all views
            document.querySelectorAll('.view-panel').forEach(v => v.classList.add('hidden'));
            
            // Show target view
            document.getElementById(`view-${viewName}`).classList.remove('hidden');
            
            // Update active menu item
            document.querySelectorAll('.menu-item').forEach(item => {
                if (item.getAttribute('data-view') === viewName) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const role = ((adminObj && adminObj.role) || localStorage.getItem('user_role') || 'admin').toLowerCase();
            
            // Update Headers
            const headers = {
                'dashboard': ['Dashboard', 'School overview and metrics'],
                'users': ['Users Directory', 'Search and manage student, teacher and parent credentials'],
                'teachers': ['Academic Staff Directory', 'Specializations and qualifications roster'],
                'grades': ['Grades Management', 'Assign class teachers to school grade levels'],
                'subjects-management': ['Subjects Management', 'Create new subjects and assign them to specific grades'],
                'attendance': ['Student Attendance Logs', 'Real-time clock-in and clock-out logs for students'],
                'teacher-attendance': ['Teacher Attendance Logs', 'Real-time clock-in and clock-out logs for teachers'],
                'teacher-own-attendance': ['My Attendance Logs', 'Your personal real-time clock-in and clock-out logs'],
                'timetable': ['Manage Timetable', 'Configure daily schedules for specific classes'],
                'messages': ['Messaging Hub', 'Chat directly with teachers, students and staff'],
                'fees': ['Fees & Payments', 'Manage monthly tuition fees for each student'],
                'salaries': ['Teacher Salaries', 'Manage monthly salary payments for teachers'],
                'emergency': ['🚨 Emergency Broadcast Center', 'Trigger instant high-priority emergency push notifications to all users'],
                'notices': ['Notices & Announcements', 'Broadcast target-audience filtered messages to users'],
                'events': ['Events Calendar', 'Configure school calendar and event details'],
                'gallery': ['Event Photo Gallery', 'Organize and upload multi-photo event albums for school activities'],
                'complaints': role === 'admin' 
                    ? ['Complaints & Feedback', 'Review and resolve concerns submitted by students and parents']
                    : ['Submit Complaint', 'Report concerns or issues to the administration'],
                'leaves': role === 'admin' 
                    ? ['Leave Requests', 'Review and approve/reject staff leave requests']
                    : ['Request Leave', 'Submit and track your leave requests'],
                'teacher-timetable': ['My Schedule', 'Daily schedule and classrooms assignment list'],
                'meal-plan': ['Meal Plan', 'Daily school meal schedule for breakfast, lunch and snacks'],
                'teacher-materials': ['Upload Materials', 'Publish curriculum content and assignments for grades'],
                'teacher-uploaded-materials': ['Uploaded Materials', 'History of all materials and assignments published'],
                'teacher-submissions': ['Student Assignment Submissions', 'Assess papers and assign final grades to students'],
                'teacher-class-results': ['My Class Results', 'All subject marks, total scores, averages, and ranks for students in your assigned class'],
                'class-rankings': ['Class Rankings', 'View and export student academic ranks and scores by grade, term, and year'],
                'teacher-salaries': ['My Salaries', 'Overview of monthly compensation paid by school administration'],
                'teacher-qr': ['My Attendance QR', 'Show or download your QR code for daily attendance'],
                'admin-content': ['Teacher Content Library', 'Browse all materials and assignments uploaded by teachers'],
                'ai-performance': ['AI Performance Insights', 'Academic performance analysis & AI-powered evaluations'],
            };
            
            // Restrict admin-only views
            if (viewName === 'class-rankings' && role !== 'admin') {
                switchView('dashboard');
                return;
            }

            document.getElementById('workspace-title').innerText = headers[viewName] ? headers[viewName][0] : 'Workspace';
            document.getElementById('workspace-subtitle').innerText = headers[viewName] ? headers[viewName][1] : '';
            
            // Fetch view-specific data
            if (viewName === 'users') fetchUsers();
            if (viewName === 'teachers') fetchTeachers();
            if (viewName === 'grades') fetchGrades();
            if (viewName === 'subjects-management') fetchSubjectsManagement();
            if (viewName === 'attendance') {
                if (role === 'admin') {
                    document.getElementById('attendance-tabs-container').style.display = 'flex';
                    switchAttendanceTab('students');
                } else {
                    document.getElementById('attendance-tabs-container').style.display = 'none';
                    switchAttendanceTab('students');
                }
            }
            if (viewName === 'teacher-own-attendance') fetchTeacherOwnAttendance();
            if (viewName === 'teacher-class-results') fetchTeacherClassResults();
            if (viewName === 'ai-performance') initAiPerformanceView();
            if (viewName === 'timetable') fetchTimetableRoster();
            if (viewName === 'messages') fetchConversationsAndContacts();
            if (viewName === 'fees') {
                if (role === 'admin') {
                    document.getElementById('fees-tabs-container').style.display = 'flex';
                    switchFeesTab(activeFeesTab);
                } else {
                    document.getElementById('fees-tabs-container').style.display = 'none';
                    switchFeesTab('students');
                }
            }
            if (viewName === 'emergency') switchEmergencySubTab('trigger');
            if (viewName === 'notices') fetchNotices();
            if (viewName === 'events') fetchEvents();
            if (viewName === 'gallery') fetchGalleries();
            if (viewName === 'complaints') {
                if (role === 'admin') {
                    document.getElementById('complaints-admin-section').classList.remove('hidden');
                    document.getElementById('complaints-teacher-section').classList.add('hidden');
                    fetchComplaints();
                } else {
                    document.getElementById('complaints-admin-section').classList.add('hidden');
                    document.getElementById('complaints-teacher-section').classList.remove('hidden');
                    switchTeacherComplaintsTab('new');
                }
            }
            if (viewName === 'leaves') {
                if (role === 'admin') {
                    document.getElementById('leaves-admin-section').classList.remove('hidden');
                    document.getElementById('leaves-teacher-section').classList.add('hidden');
                    fetchLeavesAdmin();
                } else {
                    document.getElementById('leaves-admin-section').classList.add('hidden');
                    document.getElementById('leaves-teacher-section').classList.remove('hidden');
                    switchTeacherLeavesTab('new');
                }
            }
            if (viewName === 'teacher-qr') {
                const admin = JSON.parse(localStorage.getItem('admin_user') || '{}');
                document.getElementById('teacher-qr-name').innerText = admin.name || 'Teacher';
                const qrData = `teacher:${admin.id}`;
                const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(qrData)}`;
                document.getElementById('teacher-qr-image').src = qrUrl;
            }
            if (viewName === 'teacher-timetable') fetchTeacherTimetable();
            if (viewName === 'meal-plan') {
                const canEdit = role === 'admin';
                document.getElementById('meal-plan-save-btn').style.display = canEdit ? 'inline-flex' : 'none';
                document.getElementById('meal-plan-readonly-note').classList.toggle('hidden', canEdit);
                ['meal-plan-meal', 'meal-plan-notes'].forEach(id => {
                    document.getElementById(id).disabled = !canEdit;
                });
                fetchMealPlans();
            }
            if (viewName === 'teacher-materials') setupTeacherDashboard(adminObj);
            if (viewName === 'teacher-uploaded-materials') fetchTeacherUploadedMaterials();
            if (viewName === 'teacher-submissions') fetchStudentSubmissions();
            if (viewName === 'teacher-salaries') fetchTeacherSalaries();
            if (viewName === 'exam-results') fetchExamResults();
            if (viewName === 'class-rankings') fetchClassRankingsData();
            if (viewName === 'admin-content') fetchAdminContent();
        }

        // Add events to sidebar buttons
        document.querySelectorAll('.menu-item[data-view]').forEach(item => {
            item.addEventListener('click', (e) => {
                const view = e.currentTarget.getAttribute('data-view');
                switchView(view);
            });
        });

        // 3. API Fetch Operations
        async function apiRequest(endpoint, options = {}) {
            const token = localStorage.getItem('admin_token');
            const headers = {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                ...options.headers
            };
            
            const res = await fetch(`${API_BASE}${endpoint}`, { ...options, headers });
            
            if (res.status === 401) {
                // Token expired
                localStorage.removeItem('admin_token');
                localStorage.removeItem('admin_user');
                if (chatMessagesPollingInterval) {
                    clearInterval(chatMessagesPollingInterval);
                    chatMessagesPollingInterval = null;
                }
                showLogin();
                throw new Error('Session expired. Please log in again.');
            }
            
            const data = await res.json().catch(() => ({}));
            
            if (!res.ok) {
                if (data.errors) {
                    const msg = Object.values(data.errors).map(arr => arr[0]).join(', ');
                    throw new Error(msg || 'Validation failed.');
                }
                throw new Error(data.message || data.error || `Request failed with status ${res.status}`);
            }
            
            return data;
        }

        async function fetchDashboardMetrics() {
            try {
                const uRes = await apiRequest('/admin/users');
                const tRes = await apiRequest('/admin/teachers');
                
                if (uRes.success) {
                    document.getElementById('val-users').innerText = uRes.data.length;
                    const studentCount = uRes.data.filter(u => u.role && u.role.toLowerCase() === 'student').length;
                    document.getElementById('val-students').innerText = studentCount;
                }
                if (tRes.success) document.getElementById('val-teachers').innerText = tRes.data.length;
            } catch (err) {
                console.error('Metrics loading issue:', err);
            }
        }

        // Fetch & render Users
        async function fetchUsers() {
            const tbody = document.getElementById('users-table-body');
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #697386; padding: 30px;">Loading users list...</td></tr>';
            
            try {
                const res = await apiRequest('/admin/users');
                if (res.success) {
                    usersData = res.data;
                    renderUsers();
                } else {
                    tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: #ff4d4f; padding: 30px;">${res.message || 'Error loading users.'}</td></tr>`;
                }
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: #ff4d4f; padding: 30px;">Connection failed.</td></tr>`;
            }
        }

        function renderUsers() {
            const tbody = document.getElementById('users-table-body');
            const search = document.getElementById('users-search').value.toLowerCase();
            const filter = document.getElementById('users-filter').value;
            
            const filtered = usersData.filter(user => {
                const matchesSearch = user.name.toLowerCase().includes(search) || 
                                      user.email.toLowerCase().includes(search) ||
                                      (user.phone && user.phone.includes(search));
                const matchesRole = filter === 'all' || user.role.toLowerCase() === filter.toLowerCase();
                return matchesSearch && matchesRole;
            });
            
            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #697386; padding: 30px;">No matching users found</td></tr>';
                return;
            }
            
            tbody.innerHTML = filtered.map(user => {
                const isStudent = user.role.toLowerCase() === 'student';
                const isTeacher = user.role.toLowerCase() === 'teacher';
                const isAdmin = user.role.toLowerCase() === 'admin';
                const studentId = (isStudent && user.student) ? user.student.id : null;
                const safeName  = user.name.replace(/'/g, "\\'");

                let actionBtnHtml = `<div style="display:flex; gap:5px; align-items:center; flex-wrap:wrap;">`;

                if (isStudent) {
                    actionBtnHtml += `<button onclick="openViewStudentModal(${user.id})" style="padding:4px 9px; font-size:11px; font-weight:600; border:none; border-radius:6px; background:#4CAF50; color:white; cursor:pointer;">View</button>`;
                }
                if (isTeacher) {
                    actionBtnHtml += `<button onclick="openViewTeacherModal(${user.id})" style="padding:4px 9px; font-size:11px; font-weight:600; border:none; border-radius:6px; background:#4CAF50; color:white; cursor:pointer;">View</button>`;
                }
                if (isStudent || isTeacher) {
                    actionBtnHtml += `<button onclick="showUserQrCode(${user.id}, '${safeName}', '${user.role}', ${studentId})" style="padding:4px 9px; font-size:11px; font-weight:600; border:none; border-radius:6px; background:#9c27b0; color:white; cursor:pointer;">QR</button>`;
                }
                if (!isAdmin) {
                    actionBtnHtml += `<button onclick="openEditUserModal(${user.id})" style="padding:4px 9px; font-size:11px; font-weight:600; border:none; border-radius:6px; background:#0077be; color:white; cursor:pointer;">Edit</button>`;
                }
                actionBtnHtml += `<button onclick="openChangePasswordModal(${user.id}, '${safeName}')" style="padding:4px 9px; font-size:11px; font-weight:600; border:none; border-radius:6px; background:#ff9800; color:white; cursor:pointer;">Password</button>`;
                if (!isAdmin) {
                    actionBtnHtml += `<button onclick="confirmDeleteUser(${user.id}, '${safeName}')" style="padding:4px 9px; font-size:11px; font-weight:600; border:none; border-radius:6px; background:#ff4d4f; color:white; cursor:pointer;">Delete</button>`;
                }
                actionBtnHtml += `</div>`;

                let displayId = `#${user.id}`;
                if (isStudent && user.student && user.student.student_id) displayId = user.student.student_id;
                if (isTeacher && user.teacher && user.teacher.employee_id) displayId = user.teacher.employee_id;

                return `
                    <tr>
                        <td style="font-weight:700; color:#0077be; font-size:12px;">${displayId}</td>
                        <td style="font-weight:600;">${user.name}</td>
                        <td>${user.email}</td>
                        <td>${user.phone || '—'}</td>
                        <td>${user.address || '—'}</td>
                        <td><span class="badge badge-${user.role.toLowerCase()}">${user.role}</span></td>
                        <td>${actionBtnHtml}</td>
                    </tr>
                `;
            }).join('');
        }

        function openViewStudentModal(userId) {
            const user = usersData.find(u => String(u.id) === String(userId));
            if (!user) return;
            const student = user.student || {};

            const headerSub = document.getElementById('view-student-header-sub');
            if (headerSub) headerSub.innerText = `${user.name} (${student.student_id || ('#' + user.id)})`;
            
            const setVal = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.innerText = val || '—';
            };

            setVal('v-student-name', user.name);
            setVal('v-student-id', student.student_id || ('#' + user.id));
            setVal('v-student-grade', student.grade ? `Grade ${student.grade}` : '—');
            setVal('v-student-section', student.class ? `Class ${student.class}` : '—');
            setVal('v-student-email', user.email);
            setVal('v-student-phone', user.phone);
            setVal('v-student-dob', user.dob);
            setVal('v-student-address', user.address);

            setVal('v-parent-name', student.parent_name);
            setVal('v-parent-email', student.parent_email);
            setVal('v-parent-phone', student.parent_phone);

            const modal = document.getElementById('view-student-modal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeViewStudentModal() {
            const modal = document.getElementById('view-student-modal');
            if (modal) modal.classList.add('hidden');
        }

        function openViewTeacherModal(userId) {
            const user = usersData.find(u => String(u.id) === String(userId));
            if (!user) return;
            const teacher = user.teacher || {};

            const headerSub = document.getElementById('view-teacher-header-sub');
            if (headerSub) headerSub.innerText = `${user.name} (${teacher.employee_id || ('#' + user.id)})`;
            
            const setVal = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.innerText = val || '—';
            };

            setVal('v-teacher-name', user.name);
            setVal('v-teacher-id', teacher.employee_id || ('#' + user.id));
            setVal('v-teacher-subject', teacher.subject_specialization);
            setVal('v-teacher-qualification', teacher.qualification);
            setVal('v-teacher-email', user.email);
            setVal('v-teacher-phone', user.phone);
            setVal('v-teacher-dob', user.dob);
            setVal('v-teacher-salary', teacher.salary ? `LKR ${parseFloat(teacher.salary).toLocaleString('en-US', {minimumFractionDigits: 2})}` : '—');
            setVal('v-teacher-address', user.address);

            const modal = document.getElementById('view-teacher-modal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeViewTeacherModal() {
            const modal = document.getElementById('view-teacher-modal');
            if (modal) modal.classList.add('hidden');
        }


        // Users listeners
        document.getElementById('users-search').addEventListener('input', renderUsers);
        document.getElementById('users-filter').addEventListener('change', renderUsers);

        // Fetch & render Teachers
        async function fetchTeachers() {
            const tbody = document.getElementById('teachers-table-body');
            tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: #697386; padding: 30px;">Loading teachers...</td></tr>';
            
            try {
                const res = await apiRequest('/admin/teachers');
                if (res.success) {
                    teachersData = res.data;
                    renderTeachers();
                } else {
                    tbody.innerHTML = `<tr><td colspan="5" style="text-align: center; color: #ff4d4f; padding: 30px;">${res.message || 'Error loading teachers.'}</td></tr>`;
                }
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="5" style="text-align: center; color: #ff4d4f; padding: 30px;">Connection failed.</td></tr>`;
            }
        }

        function renderTeachers() {
            const tbody = document.getElementById('teachers-table-body');
            const search = document.getElementById('teachers-search').value.toLowerCase();
            
            const filtered = teachersData.filter(user => {
                const teacherInfo = user.teacher || {};
                const matchesSearch = user.name.toLowerCase().includes(search) ||
                                      (teacherInfo.employee_id && teacherInfo.employee_id.toLowerCase().includes(search)) ||
                                      (teacherInfo.subject_specialization && teacherInfo.subject_specialization.toLowerCase().includes(search));
                return matchesSearch;
            });
            
            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: #697386; padding: 30px;">No matching teachers found</td></tr>';
                return;
            }
            
            tbody.innerHTML = filtered.map(user => {
                const t = user.teacher || {};
                return `
                    <tr>
                        <td style="font-weight: 700; color: #9c27b0;">${t.employee_id || '—'}</td>
                        <td style="font-weight: 600;">${user.name}</td>
                        <td>${user.email}</td>
                        <td>${t.qualification || '—'}</td>
                        <td style="font-style: italic; font-weight: 500;">${t.subject_specialization || '—'}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="font-size: 12px; color: #697386; font-weight: 600;">LKR</span>
                                <input type="number" value="${t.salary || '50000'}" style="width: 100px; padding: 6px 8px; border: 1.5px solid rgba(0,0,0,0.08); border-radius: 8px; font-weight: 700; color: #0077be; outline: none; background: rgba(255,255,255,0.9);" onchange="updateTeacherSalary(${user.id}, this.value)">
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }
        
        async function updateTeacherSalary(teacherId, salary) {
            try {
                const res = await apiRequest(`/admin/teachers/${teacherId}`, {
                    method: 'PUT',
                    body: JSON.stringify({ salary: parseFloat(salary) })
                });
                if (res.success) {
                    showToast('Teacher salary updated successfully!', 'success');
                    fetchTeachers();
                } else {
                    showToast(res.message || 'Failed to update salary.', 'error');
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }
        
        document.getElementById('teachers-search').addEventListener('input', renderTeachers);

        // ─── Grades Management View ───────────────────────────────────────────
        let gradesData = [];

        async function fetchGrades() {
            const tbody = document.getElementById('grades-table-body');
            tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #697386; padding: 30px;">Loading grades list...</td></tr>';
            
            try {
                const res = await apiRequest('/admin/grades');
                if (res.success) {
                    gradesData = res.data;
                    renderGradesTable();
                } else {
                    tbody.innerHTML = `<tr><td colspan="4" style="text-align: center; color: #ff4d4f; padding: 30px;">${res.message || 'Error loading grades.'}</td></tr>`;
                }
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="4" style="text-align: center; color: #ff4d4f; padding: 30px;">Connection failed: ${err.message}</td></tr>`;
            }
        }

        function renderGradesTable() {
            const tbody = document.getElementById('grades-table-body');
            const searchInput = document.getElementById('grades-search');
            const search = (searchInput ? searchInput.value : '').toLowerCase();
            
            const filtered = gradesData.filter(grade => {
                const gradeName = (grade.name || '').toLowerCase();
                const section = (grade.section || '').toLowerCase();
                
                let teacherName = '';
                if (grade.class_teacher) {
                    if (grade.class_teacher.user) {
                        teacherName = (grade.class_teacher.user.name || '').toLowerCase();
                    } else {
                        const matchedUser = teachersData.find(u => u.teacher && u.teacher.id === grade.class_teacher_id);
                        if (matchedUser) {
                            teacherName = (matchedUser.name || '').toLowerCase();
                        }
                    }
                }
                
                return gradeName.includes(search) || section.includes(search) || teacherName.includes(search);
            });
            
            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #697386; padding: 30px;">No matching grades found</td></tr>';
                return;
            }
            
            tbody.innerHTML = filtered.map(grade => {
                let teacherName = null;
                let empId = null;
                
                if (grade.class_teacher) {
                    empId = grade.class_teacher.employee_id;
                    if (grade.class_teacher.user) {
                        teacherName = grade.class_teacher.user.name;
                    } else {
                        const matchedUser = teachersData.find(u => u.teacher && u.teacher.id === grade.class_teacher_id);
                        if (matchedUser) {
                            teacherName = matchedUser.name;
                        }
                    }
                }
                
                const teacherText = teacherName ? `${teacherName} (${empId || 'No ID'})` : '<span style="color:#697386; font-style:italic;">No Class Teacher Assigned</span>';
                const currentTeacherId = grade.class_teacher_id || '';
                const safeGradeName = grade.name.replace(/'/g, "\\'");
                
                return `
                    <tr>
                        <td style="font-weight: 600;">${grade.name}</td>
                        <td>${teacherText}</td>
                        <td>
                            <button class="btn-toggle-fee pay" onclick="openAssignTeacherModal(${grade.id}, '${safeGradeName}', '${currentTeacherId}')" style="padding: 4px 10px; font-size: 11px; font-weight:600; width:auto; margin-top:0; background:#0077be;">
                                Assign Teacher
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function openAssignTeacherModal(gradeId, gradeName, currentTeacherId) {
            document.getElementById('assign-grade-id').value = gradeId;
            document.getElementById('assign-grade-name').value = gradeName;
            
            const select = document.getElementById('assign-teacher-select');
            select.innerHTML = '<option value="">-- No Class Teacher --</option>';
            
            try {
                if (teachersData.length === 0) {
                    const res = await apiRequest('/admin/teachers');
                    if (res.success) {
                        teachersData = res.data;
                    }
                }
                
                teachersData.forEach(user => {
                    if (user.teacher) {
                        const opt = document.createElement('option');
                        opt.value = user.teacher.id;
                        opt.textContent = `${user.name} (${user.teacher.employee_id || 'No ID'})`;
                        if (String(user.teacher.id) === String(currentTeacherId)) {
                            opt.selected = true;
                        }
                        select.appendChild(opt);
                    }
                });
            } catch (err) {
                console.error('Failed to load teachers for select:', err);
            }
            
            document.getElementById('assign-teacher-modal').classList.remove('hidden');
        }

        function closeAssignTeacherModal() {
            document.getElementById('assign-teacher-modal').classList.add('hidden');
        }

        let subjectsHistoryData = [];

        function renderSubjectsHistoryTable(subjects) {
            const tableBody = document.getElementById('subjects-history-table-body');
            // Filter out subjects with no grade assigned
            const filtered = subjects.filter(sub => sub.grade && sub.grade.trim() !== '');
            if (filtered.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #697386; padding: 30px;">No subjects found matching your search.</td></tr>';
                return;
            }
            
            tableBody.innerHTML = filtered.map(sub => {
                let dateStr = '—';
                if (sub.created_at) {
                    dateStr = new Date(sub.created_at).toLocaleDateString('en-US', {
                        year: 'numeric', month: 'short', day: 'numeric'
                    });
                }
                const safeName = (sub.subject_name || '').replace(/'/g, "\\'");
                const safeGrade = (sub.grade || '').replace(/'/g, "\\'");
                return `
                    <tr>
                        <td style="font-weight: 600;">${sub.grade || '—'}</td>
                        <td>${sub.subject_name || '—'}</td>
                        <td>${dateStr}</td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <button onclick="openEditSubjectModal(${sub.id}, '${safeName}', '${safeGrade}')" 
                                    style="padding:5px 10px; font-size:11px; background:#0077be; color:white; border:none; border-radius:5px; cursor:pointer; font-weight:600;">
                                    Edit
                                </button>
                                <button onclick="confirmDeleteSubject(${sub.id}, '${safeName}')" 
                                    style="padding:5px 10px; font-size:11px; background:#e53e3e; color:white; border:none; border-radius:5px; cursor:pointer; font-weight:600;">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function filterSubjectsHistory() {
            const search = (document.getElementById('subjects-history-search')?.value || '').toLowerCase();
            const filtered = subjectsHistoryData.filter(sub => {
                const gradeStr = (sub.grade || '').toLowerCase();
                const nameStr = (sub.subject_name || '').toLowerCase();
                return gradeStr.includes(search) || nameStr.includes(search);
            });
            renderSubjectsHistoryTable(filtered);
        }

        let teacherUploadedMaterialsData = [];

        function renderTeacherUploadedMaterials(subjects) {
            const tableBody = document.getElementById('teacher-uploaded-table-body');
            if (subjects.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #697386; padding: 30px;">No uploaded materials found matching your search.</td></tr>';
                return;
            }
            
            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const role = (localStorage.getItem('user_role') || adminObj.role || 'teacher').toLowerCase();

            tableBody.innerHTML = subjects.map(sub => {
                let dateStr = '—';
                if (sub.created_at) {
                    dateStr = new Date(sub.created_at).toLocaleDateString('en-US', {
                        year: 'numeric', month: 'short', day: 'numeric'
                    });
                }
                const pdfLink = sub.pdf ? `<a href="${getFileUrl(sub.pdf)}" target="_blank" style="color:#0077be; text-decoration:none; font-weight:600;">View PDF</a>` : '<span style="color:#6c757d; font-size: 12px;">None</span>';
                let assignmentLink = sub.assignment ? `<a href="${getFileUrl(sub.assignment)}" target="_blank" style="color:#9c27b0; text-decoration:none; font-weight:600;">View Assignment</a>` : '<span style="color:#6c757d; font-size: 12px;">None</span>';
                
                let submitBtn = '';
                if (sub.assignment) {
                    if (sub.is_submitted) {
                        submitBtn = `<span class="badge status-present" style="background:#4CAF50; color:white; font-size:11px; margin-left:8px;">Submitted</span>`;
                    } else if (role === 'student') {
                        submitBtn = `<button class="btn-toggle-fee pay" onclick="openStudentSubmitModal(${sub.id}, '${(sub.subject_name || '').replace(/'/g, "\\'")}', '${(sub.grade || '').replace(/'/g, "\\'")}')" style="padding:4px 8px; font-size:11px; margin-left:8px; border:none; background:#0077be; color:white; cursor:pointer;">Submit Answer</button>`;
                    }
                }

                const deleteBtn = (role === 'teacher' || role === 'admin')
                    ? `<button class="btn-toggle-fee unpay" onclick="deleteTeacherUploadedMaterial(${sub.id})" style="padding: 4px 10px; font-size: 11px; background: #ff4d4f; border: none; border-radius: 6px; color: white; cursor: pointer;">Delete</button>`
                    : '—';

                return `
                    <tr>
                        <td style="font-weight: 600; color: #4CAF50;">${sub.grade || '—'}</td>
                        <td style="font-weight: 500;">${sub.subject_name || '—'}</td>
                        <td style="font-weight: 500; color: #1a1f36;">${sub.topic || '—'}</td>
                        <td>${pdfLink}</td>
                        <td>${assignmentLink} ${submitBtn}</td>
                        <td>${dateStr}</td>
                        <td>${deleteBtn}</td>
                    </tr>
                `;
            }).join('');
        }

        function filterTeacherUploadedMaterials() {
            const search = (document.getElementById('teacher-uploaded-search')?.value || '').toLowerCase();
            const filtered = teacherUploadedMaterialsData.filter(sub => {
                const gradeStr = (sub.grade || '').toLowerCase();
                const nameStr = (sub.subject_name || '').toLowerCase();
                const topicStr = (sub.topic || '').toLowerCase();
                return gradeStr.includes(search) || nameStr.includes(search) || topicStr.includes(search);
            });
            renderTeacherUploadedMaterials(filtered);
        }

        async function fetchTeacherUploadedMaterials() {
            const tableBody = document.getElementById('teacher-uploaded-table-body');
            tableBody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #697386; padding: 30px;">Loading your materials...</td></tr>';
            
            try {
                const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
                const role = (localStorage.getItem('user_role') || adminObj.role || 'teacher').toLowerCase();
                const teacherId = adminObj && adminObj.teacher ? adminObj.teacher.id : null;
                const studentGrade = adminObj && adminObj.student ? adminObj.student.grade : null;

                const res = await apiRequest(`/subjects?user_id=${adminObj.id || ''}`);
                if (res.success && res.data && Array.isArray(res.data)) {
                    const uploadedOnly = res.data.filter(s => {
                        const hasMaterial = s.pdf || s.assignment;
                        if (role === 'teacher' && teacherId) {
                            return hasMaterial && (s.teacher_id === teacherId || !s.teacher_id);
                        } else if (role === 'student' && studentGrade) {
                            return hasMaterial && s.grade && s.grade.toLowerCase().includes(studentGrade.toLowerCase());
                        }
                        return hasMaterial;
                    });
                    
                    if (uploadedOnly.length === 0) {
                        teacherUploadedMaterialsData = [];
                        renderTeacherUploadedMaterials([]);
                    } else {
                        teacherUploadedMaterialsData = uploadedOnly.sort((a, b) => {
                            const gradeA = a.grade || '';
                            const gradeB = b.grade || '';
                            if (gradeA !== gradeB) return gradeA.localeCompare(gradeB);
                            if (!a.created_at || !b.created_at) return 0;
                            return new Date(b.created_at) - new Date(a.created_at);
                        });
                        filterTeacherUploadedMaterials();
                    }
                } else {
                    tableBody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #697386; padding: 30px;">Failed to load materials.</td></tr>';
                }
            } catch (err) {
                console.error("Failed to load teacher materials", err);
                tableBody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: red; padding: 30px;">Network error loading materials.</td></tr>';
            }
        }

        async function deleteTeacherUploadedMaterial(id) {
            if (!confirm('Are you sure you want to delete this uploaded material / assignment?')) return;
            try {
                const res = await apiRequest(`/subjects/${id}`, { method: 'DELETE' });
                showToast(res.message || 'Deleted successfully', 'success');
                fetchTeacherUploadedMaterials();
            } catch (err) {
                showToast(err.message || 'Failed to delete', 'error');
            }
        }

        async function fetchSubjectsManagement() {
            const gradeSelect = document.getElementById('add-subject-grade-select');
            const tableBody = document.getElementById('subjects-history-table-body');
            
            gradeSelect.innerHTML = '<option value="">Loading grades...</option>';
            tableBody.innerHTML = '<tr><td colspan="3" style="text-align: center; color: #697386; padding: 30px;">Loading subjects...</td></tr>';
            
            try {
                // Fetch grades for dropdown
                const gradesRes = await apiRequest('/admin/grades');
                gradeSelect.innerHTML = '<option value="">Select a Grade</option>';
                if (gradesRes.success && gradesRes.data) {
                    gradesRes.data.forEach(g => {
                        const opt = document.createElement('option');
                        const val = g.name.trim();
                        opt.value = val;
                        opt.textContent = val;
                        gradeSelect.appendChild(opt);
                    });
                }
                
                // Fetch subjects for history table
                const subjectsRes = await apiRequest('/subjects');
                if (subjectsRes.success && subjectsRes.data && Array.isArray(subjectsRes.data)) {
                    if (subjectsRes.data.length === 0) {
                        subjectsHistoryData = [];
                        renderSubjectsHistoryTable([]);
                    } else {
                        // Sort by created_at descending if available
                        subjectsHistoryData = subjectsRes.data.sort((a, b) => {
                            if (!a.created_at || !b.created_at) return 0;
                            return new Date(b.created_at) - new Date(a.created_at);
                        });
                        
                        // Apply existing filter if any, or render all
                        filterSubjectsHistory();
                    }
                } else {
                    tableBody.innerHTML = '<tr><td colspan="3" style="text-align: center; color: #697386; padding: 30px;">Failed to load subjects.</td></tr>';
                }
            } catch (err) {
                console.error("Failed to load subjects management data", err);
                gradeSelect.innerHTML = '<option value="">Error loading grades</option>';
                tableBody.innerHTML = '<tr><td colspan="3" style="text-align: center; color: red; padding: 30px;">Network error loading subjects.</td></tr>';
            }
        }

        async function submitAddSubjectToGrade(event) {
            event.preventDefault();
            const grade = document.getElementById('add-subject-grade-select').value;
            const subjectName = document.getElementById('add-subject-name').value.trim();
            
            if (!grade || !subjectName) {
                showToast('Please select a grade and enter a subject name.', 'error');
                return;
            }
            
            try {
                const formData = new FormData();
                formData.append('grade', grade);
                formData.append('subject_name', subjectName);
                
                const res = await fetch(`${API_BASE}/subjects`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('admin_token')}`
                    },
                    body: formData
                });
                
                const data = await res.json();
                if (data.success) {
                    showToast(`Subject '${subjectName}' added to ${grade} successfully!`, 'success');
                    document.getElementById('add-subject-form').reset();
                    // Refresh the history table
                    fetchSubjectsManagement();
                } else {
                    showToast(data.message || 'Error adding subject', 'error');
                }
            } catch (err) {
                console.error("Failed to add subject", err);
                showToast('Network error while adding subject', 'error');
            }
        }

        async function submitAssignTeacher(event) {
            event.preventDefault();
            const gradeId = document.getElementById('assign-grade-id').value;
            const teacherIdVal = document.getElementById('assign-teacher-select').value;
            const class_teacher_id = teacherIdVal === '' ? null : parseInt(teacherIdVal);
            
            try {
                const res = await apiRequest(`/admin/grades/${gradeId}/assign-teacher`, {
                    method: 'PUT',
                    body: JSON.stringify({ class_teacher_id })
                });
                
                if (res.success) {
                    showToast('Teacher assigned successfully!', 'success');
                    closeAssignTeacherModal();
                    fetchGrades();
                } else {
                    showToast(res.message || 'Failed to assign teacher', 'error');
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        async function getTeacherAssignedClassGrades() {
            if (window.currentTeacherClassGrades && window.currentTeacherClassGrades.length > 0) {
                return window.currentTeacherClassGrades;
            }
            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            try {
                const gradesRes = await apiRequest('/admin/grades');
                if (gradesRes.success && Array.isArray(gradesRes.data)) {
                    gradesData = gradesRes.data;
                    const assigned = [];
                    gradesRes.data.forEach(g => {
                        if (isClassTeacherOfGrade(g, adminObj) && g.name) {
                            assigned.push(g.name.trim());
                        }
                    });
                    window.currentTeacherClassGrades = assigned;
                    return assigned;
                }
            } catch(e) {}
            return window.currentTeacherClassGrades || [];
        }

        async function populateAttendanceGradeFilter() {
            const filterEl = document.getElementById('attendance-grade-filter');
            if (!filterEl) return;

            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const role = ((adminObj && adminObj.role) || localStorage.getItem('user_role') || 'admin').toLowerCase();

            if (role === 'teacher') {
                const assignedGrades = await getTeacherAssignedClassGrades();
                const previousVal = filterEl.value;
                filterEl.innerHTML = '';

                if (!assignedGrades || assignedGrades.length === 0) {
                    const opt = document.createElement('option');
                    opt.value = '';
                    opt.textContent = 'No Assigned Class';
                    filterEl.appendChild(opt);
                } else {
                    assignedGrades.forEach(g => {
                        const rawNum = String(g).replace(/Grade /i, '').trim();
                        const label = String(g).toLowerCase().startsWith('grade') ? g : `Grade ${g}`;
                        const opt = document.createElement('option');
                        opt.value = rawNum;
                        opt.textContent = label;
                        filterEl.appendChild(opt);
                    });

                    if (assignedGrades.length > 1) {
                        const allAssignedOpt = document.createElement('option');
                        allAssignedOpt.value = '';
                        allAssignedOpt.textContent = 'All Assigned Classes';
                        filterEl.insertBefore(allAssignedOpt, filterEl.firstChild);
                    }

                    if (previousVal && Array.from(filterEl.options).some(o => o.value === previousVal)) {
                        filterEl.value = previousVal;
                    } else {
                        filterEl.selectedIndex = 0;
                    }
                }
            } else {
                // Admin: show all grades
                const previousVal = filterEl.value;
                filterEl.innerHTML = '<option value="">All Grades</option>';

                if (!gradesData || gradesData.length === 0) {
                    try {
                        const gRes = await apiRequest('/admin/grades');
                        if (gRes.success && Array.isArray(gRes.data)) {
                            gradesData = gRes.data;
                        }
                    } catch (e) {}
                }

                const uniqueGrades = new Set();
                if (gradesData && gradesData.length > 0) {
                    gradesData.forEach(g => {
                        const raw = (g.name || '').replace(/Grade /i, '').trim();
                        if (raw) uniqueGrades.add(raw);
                    });
                }
                for (let i = 1; i <= 12; i++) {
                    uniqueGrades.add(String(i));
                }

                const sortedGrades = Array.from(uniqueGrades).sort((a, b) => parseInt(a) - parseInt(b));
                sortedGrades.forEach(gradeNum => {
                    const opt = document.createElement('option');
                    opt.value = gradeNum;
                    opt.textContent = `Grade ${gradeNum}`;
                    filterEl.appendChild(opt);
                });

                if (previousVal && Array.from(filterEl.options).some(o => o.value === previousVal)) {
                    filterEl.value = previousVal;
                }
            }
        }

        // Fetch & render Attendance
        async function fetchAttendance(gradeFilter = null) {
            const tbody = document.getElementById('attendance-table-body');
            tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; color: #697386; padding: 30px;">Loading attendance logs...</td></tr>';
            
            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const role = ((adminObj && adminObj.role) || localStorage.getItem('user_role') || 'admin').toLowerCase();

            await populateAttendanceGradeFilter();

            const filterEl = document.getElementById('attendance-grade-filter');
            const activeFilter = (gradeFilter !== null && gradeFilter !== undefined) 
                ? gradeFilter 
                : (filterEl ? filterEl.value : '');

            if (role === 'teacher') {
                const assignedGrades = window.currentTeacherClassGrades || [];
                if (assignedGrades.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="8" style="text-align: center; color: #697386; padding: 50px 20px;">
                                <div style="font-size:36px; margin-bottom:10px;">📋</div>
                                <h3 style="margin:0 0 6px 0; color:#1a1f36; font-size:16px;">You are not assigned as a Class Teacher</h3>
                                <p style="margin:0; font-size:13px; color:#697386;">Attendance logs are only visible to assigned Class Teachers and Administrators.</p>
                            </td>
                        </tr>
                    `;
                    return;
                }
            }

            try {
                let url = '/admin/attendance';
                if (activeFilter) {
                    url += `?grade=${encodeURIComponent(activeFilter)}`;
                } else if (role === 'teacher') {
                    const assignedGrades = window.currentTeacherClassGrades || [];
                    if (assignedGrades.length > 0) {
                        const firstRaw = String(assignedGrades[0]).replace(/Grade /i, '').trim();
                        url += `?grade=${encodeURIComponent(firstRaw)}`;
                    }
                }

                const res = await apiRequest(url);
                if (res.success) {
                    attendanceData = (res.data || []).filter(rec => rec.user && rec.user.role === 'student');
                    renderAttendance();
                } else {
                    tbody.innerHTML = `<tr><td colspan="8" style="text-align: center; color: #ff4d4f; padding: 30px;">${res.message || 'Error loading logs.'}</td></tr>`;
                }
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="8" style="text-align: center; color: #ff4d4f; padding: 30px;">Connection failed.</td></tr>`;
            }
        }

        function applyAttendanceGradeFilter() {
            const gradeFilter = document.getElementById('attendance-grade-filter')?.value;
            fetchAttendance(gradeFilter);
        }

        function renderAttendance() {
            const tbody = document.getElementById('attendance-table-body');
            const filtered = getFilteredStudentAttendance();
            
            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; color: #697386; padding: 30px;">No matching attendance logs found</td></tr>';
                return;
            }
            
            tbody.innerHTML = filtered.map(rec => {
                const u = rec.user || {};
                const student = u.student || {};
                const studentId = student.student_id || '—';
                const gradeLabel = student.grade ? `Grade ${student.grade}` : '—';
                return `
                    <tr>
                        <td style="font-weight: 600; color: #1a1f36;">${studentId}</td>
                        <td style="font-weight: 600;">${u.name || 'Unknown'}</td>
                        <td><span style="display:inline-block; padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; background:#e8f4fd; color:#0077be;">${gradeLabel}</span></td>
                        <td><span class="badge badge-${(u.role || 'student').toLowerCase()}">${u.role || '—'}</span></td>
                        <td>${rec.date}</td>
                        <td style="font-weight: 700; color: #4caf50;">${rec.in_time || '—'}</td>
                        <td style="font-weight: 700; color: #ff9800;">${rec.out_time || '—'}</td>
                        <td><span class="badge badge-${rec.status.toLowerCase()}">${rec.status}</span></td>
                    </tr>
                `;
            }).join('');
        }

        document.getElementById('attendance-search').addEventListener('input', renderAttendance);

        // Fetch & render Teacher Attendance
        async function fetchTeacherAttendance() {
            const tbody = document.getElementById('teacher-attendance-table-body');
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #697386; padding: 30px;">Loading teacher attendance logs...</td></tr>';
            
            try {
                const res = await apiRequest('/admin/attendance');
                if (res.success) {
                    teacherAttendanceData = res.data.filter(rec => rec.user && rec.user.role === 'teacher');
                    renderTeacherAttendance();
                } else {
                    tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: #ff4d4f; padding: 30px;">${res.message || 'Error loading logs.'}</td></tr>`;
                }
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: #ff4d4f; padding: 30px;">Connection failed.</td></tr>`;
            }
        }

        function renderTeacherAttendance() {
            const tbody = document.getElementById('teacher-attendance-table-body');
            const search = document.getElementById('teacher-attendance-search').value.toLowerCase();
            
            const filtered = teacherAttendanceData.filter(rec => {
                const u = rec.user || {};
                const teacher = u.teacher || {};
                const matchesSearch = (u.name && u.name.toLowerCase().includes(search)) ||
                                      (teacher.employee_id && teacher.employee_id.toLowerCase().includes(search)) ||
                                      (u.email && u.email.toLowerCase().includes(search)) ||
                                      rec.date.includes(search) ||
                                      (rec.status && rec.status.toLowerCase().includes(search));
                return matchesSearch;
            });
            
            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #697386; padding: 30px;">No matching teacher attendance logs found</td></tr>';
                return;
            }
            
            tbody.innerHTML = filtered.map(rec => {
                const u = rec.user || {};
                const teacher = u.teacher || {};
                const empId = teacher.employee_id || '—';
                return `
                    <tr>
                        <td style="font-weight: 600; color: #1a1f36;">${empId}</td>
                        <td style="font-weight: 600;">${u.name || 'Unknown'}</td>
                        <td>${u.email || '—'}</td>
                        <td>${rec.date}</td>
                        <td style="font-weight: 700; color: #4caf50;">${rec.in_time || '—'}</td>
                        <td style="font-weight: 700; color: #ff9800;">${rec.out_time || '—'}</td>
                        <td><span class="badge badge-${rec.status.toLowerCase()}">${rec.status}</span></td>
                    </tr>
                `;
            }).join('');
        }

        document.getElementById('teacher-attendance-search').addEventListener('input', renderTeacherAttendance);

        function switchAttendanceTab(tab) {
            const studentTabBtn = document.getElementById('attendance-tab-students');
            const teacherTabBtn = document.getElementById('attendance-tab-teachers');
            const studentPane = document.getElementById('attendance-students-pane');
            const teacherPane = document.getElementById('attendance-teachers-pane');

            if (tab === 'students') {
                studentTabBtn.style.background = '#0077be';
                studentTabBtn.style.color = 'white';
                teacherTabBtn.style.background = 'transparent';
                teacherTabBtn.style.color = '#697386';
                studentPane.classList.remove('hidden');
                teacherPane.classList.add('hidden');
                fetchAttendance();
            } else {
                teacherTabBtn.style.background = '#0077be';
                teacherTabBtn.style.color = 'white';
                studentTabBtn.style.background = 'transparent';
                studentTabBtn.style.color = '#697386';
                studentPane.classList.add('hidden');
                teacherPane.classList.remove('hidden');
                fetchTeacherAttendance();
            }
        }

        // Fetch & render Teacher's Own Attendance
        async function fetchTeacherOwnAttendance() {
            const tbody = document.getElementById('teacher-own-attendance-table-body');
            tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #697386; padding: 30px;">Loading my attendance logs...</td></tr>';
            
            try {
                const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
                const teacherUserId = adminObj.id;
                
                if (!teacherUserId) {
                    tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #ff4d4f; padding: 30px;">User details not found.</td></tr>';
                    return;
                }

                const res = await apiRequest('/attendence/user', {
                    method: 'POST',
                    body: JSON.stringify({ user_id: teacherUserId })
                });

                if (res.success) {
                    teacherOwnAttendanceData = res.data;
                    renderTeacherOwnAttendance();
                } else {
                    tbody.innerHTML = `<tr><td colspan="4" style="text-align: center; color: #ff4d4f; padding: 30px;">${res.message || 'Error loading logs.'}</td></tr>`;
                }
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="4" style="text-align: center; color: #ff4d4f; padding: 30px;">Connection failed.</td></tr>`;
            }
        }

        function renderTeacherOwnAttendance() {
            const tbody = document.getElementById('teacher-own-attendance-table-body');
            const search = document.getElementById('teacher-own-attendance-search').value.toLowerCase();
            
            const filtered = teacherOwnAttendanceData.filter(rec => {
                return rec.date.includes(search) || (rec.status && rec.status.toLowerCase().includes(search));
            });
            
            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #697386; padding: 30px;">No matching attendance logs found</td></tr>';
                return;
            }
            
            tbody.innerHTML = filtered.map(rec => {
                return `
                    <tr>
                        <td>${rec.date}</td>
                        <td style="font-weight: 700; color: #4caf50;">${rec.in_time || '—'}</td>
                        <td style="font-weight: 700; color: #ff9800;">${rec.out_time || '—'}</td>
                        <td><span class="badge badge-${rec.status.toLowerCase()}">${rec.status}</span></td>
                    </tr>
                `;
            }).join('');
        }

        document.getElementById('teacher-own-attendance-search').addEventListener('input', renderTeacherOwnAttendance);

        // 4. Timetable configuration SPA methods
        function buildTimetableFormFields() {
            const container = document.getElementById('timetable-slots-container');
            container.innerHTML = Object.entries(slotsMetadata).map(([key, label]) => `
                <div class="timetable-row" style="display: flex; align-items: center; gap: 15px; margin-bottom: 12px; background: rgba(255,255,255,0.03); padding: 8px 12px; border-radius: 8px;">
                    <span class="slot-time" style="min-width: 150px; font-weight: 600; font-size: 13px; color: #1a1a1a;">${label}</span>
                    <select name="${key}" id="slot-${key}" class="slot-input filter-select" style="flex: 1; min-width: 150px; height: 38px; border-radius: 8px; border: 1px solid #ddd; padding: 0 10px; background: white;">
                        <option value="">Select Subject</option>
                    </select>
                    <div class="slot-grade-container hidden" id="slot-grade-container-${key}" style="width: 140px; min-width: 140px;">
                        <select id="slot-grade-${key}" class="filter-select" style="width: 100%; height: 38px; border-radius: 8px; border: 1px solid #ddd; padding: 0 10px;">
                            <option value="">No Grade</option>
                            <option value="Grade 1">Grade 1</option>
                            <option value="Grade 2">Grade 2</option>
                            <option value="Grade 3">Grade 3</option>
                            <option value="Grade 4">Grade 4</option>
                            <option value="Grade 5">Grade 5</option>
                            <option value="Grade 6">Grade 6</option>
                            <option value="Grade 7">Grade 7</option>
                            <option value="Grade 8">Grade 8</option>
                            <option value="Grade 9">Grade 9</option>
                            <option value="Grade 10">Grade 10</option>
                            <option value="Grade 11">Grade 11</option>
                            <option value="Grade 12">Grade 12</option>
                        </select>
                    </div>
                </div>
            `).join('');
            
            // Attach event listener to grade select elements in the slots
            Object.keys(slotsMetadata).forEach(key => {
                const gradeSelect = document.getElementById(`slot-grade-${key}`);
                if (gradeSelect) {
                    gradeSelect.addEventListener('change', () => {
                        updateSubjectDropdowns();
                    });
                }
            });
        }

        function updateSubjectDropdowns() {
            const mainGrade = document.getElementById('timetable-grade').value;
            
            Object.keys(slotsMetadata).forEach(key => {
                const selectEl = document.getElementById(`slot-${key}`);
                if (!selectEl) return;
                
                const currentVal = selectEl.value;
                
                let targetGrade = mainGrade;
                if (timetableType === 'teacher') {
                    const rowGradeSelect = document.getElementById(`slot-grade-${key}`);
                    targetGrade = rowGradeSelect ? rowGradeSelect.value : '';
                }
                
                let subjects = [];
                if (targetGrade) {
                    subjects = subjectsHistoryData.filter(sub => 
                        sub.grade && sub.grade.trim().toLowerCase() === targetGrade.trim().toLowerCase()
                    ).map(sub => sub.subject_name);
                }
                
                // Remove duplicates
                subjects = [...new Set(subjects)];
                
                let optionsHtml = '<option value="">Select Subject / Free Slot</option>';
                const commonOptions = ['Interval', 'Break', 'Lunch', 'Assembly'];
                commonOptions.forEach(opt => {
                    optionsHtml += `<option value="${opt}">${opt}</option>`;
                });
                
                subjects.forEach(subName => {
                    if (!commonOptions.includes(subName)) {
                        optionsHtml += `<option value="${subName}">${subName}</option>`;
                    }
                });
                
                selectEl.innerHTML = optionsHtml;
                
                if (currentVal && currentVal.trim() !== '') {
                    const hasOption = Array.from(selectEl.options).some(opt => opt.value === currentVal);
                    if (!hasOption) {
                        selectEl.innerHTML += `<option value="${currentVal}">${currentVal}</option>`;
                    }
                    selectEl.value = currentVal;
                }
            });
        }

        async function fetchTimetableRoster() {
            try {
                // Fetch simple timetables list
                const res = await apiRequest('/simple-timetables');
                if (res) {
                    if (res.success) {
                        timetableRecords = res.data;
                    } else if (Array.isArray(res)) {
                        timetableRecords = res;
                    } else {
                        timetableRecords = [];
                    }
                }
                
                // Prefill the dropdown of teachers if empty
                if (teachersData.length === 0) {
                    await fetchTeachers();
                }
                populateTimetableTeachersDropdown();

                // Fetch subjects if empty
                if (subjectsHistoryData.length === 0) {
                    const subjectsRes = await apiRequest('/subjects');
                    if (subjectsRes.success && subjectsRes.data && Array.isArray(subjectsRes.data)) {
                        subjectsHistoryData = subjectsRes.data;
                    }
                }
                
                updateSubjectDropdowns();
                prefillTimetableForm();
            } catch (err) {
                console.error('Timetable loading issue:', err);
            }
        }

        function populateTimetableTeachersDropdown() {
            const select = document.getElementById('timetable-teacher');
            if (!select) return;
            
            select.innerHTML = teachersData.map(user => {
                const t = user.teacher || {};
                const name = user.name;
                const empId = t.employee_id || `ID:${user.id}`;
                return `<option value="Teacher:${user.id}">${name} (${empId})</option>`;
            }).join('');
        }

        function switchTimetableTab(type) {
            timetableType = type;
            
            document.getElementById('btn-class-timetable').classList.toggle('active', type === 'class');
            document.getElementById('btn-teacher-timetable').classList.toggle('active', type === 'teacher');
            
            if (type === 'class') {
                document.getElementById('timetable-grade-wrapper').classList.remove('hidden');
                document.getElementById('timetable-teacher-wrapper').classList.add('hidden');
            } else {
                document.getElementById('timetable-grade-wrapper').classList.add('hidden');
                document.getElementById('timetable-teacher-wrapper').classList.remove('hidden');
                
                if (teachersData.length === 0) {
                    fetchTeachers().then(() => {
                        populateTimetableTeachersDropdown();
                        updateSubjectDropdowns();
                        prefillTimetableForm();
                    });
                    return;
                }
            }
            
            // Toggle grade select visibility inside the slots
            Object.keys(slotsMetadata).forEach(key => {
                const el = document.getElementById(`slot-grade-container-${key}`);
                if (el) {
                    if (type === 'teacher') {
                        el.classList.remove('hidden');
                    } else {
                        el.classList.add('hidden');
                    }
                }
            });

            updateSubjectDropdowns();
            prefillTimetableForm();
        }

        function getSelectedTimetableGrade() {
            if (timetableType === 'class') {
                return document.getElementById('timetable-grade').value;
            } else {
                return document.getElementById('timetable-teacher').value;
            }
        }

        function prefillTimetableForm() {
            const day = document.getElementById('timetable-day').value;
            const grade = getSelectedTimetableGrade();
            
            if (!grade) return;
            
            // Find matched record
            const match = timetableRecords.find(t => 
                t.day.trim().toLowerCase() === day.trim().toLowerCase() &&
                t.grade.trim().toLowerCase() === grade.trim().toLowerCase()
            );
            
            // Reset fields
            Object.keys(slotsMetadata).forEach(key => {
                const selectEl = document.getElementById(`slot-${key}`);
                selectEl.value = '';
                const gradeSelect = document.getElementById(`slot-grade-${key}`);
                if (gradeSelect) gradeSelect.value = '';
            });
            
            // Toggle grade select visibility in slot rows matching the active timetable type
            Object.keys(slotsMetadata).forEach(key => {
                const el = document.getElementById(`slot-grade-container-${key}`);
                if (el) {
                    if (timetableType === 'teacher') {
                        el.classList.remove('hidden');
                    } else {
                        el.classList.add('hidden');
                    }
                }
            });

            // Update dropdowns based on current grade state
            updateSubjectDropdowns();

            // Fill if found
            if (match) {
                Object.keys(slotsMetadata).forEach(key => {
                    const rawVal = match[key] || '';
                    let subject = rawVal;
                    let slotGrade = '';
                    
                    if (rawVal.includes('(') && rawVal.includes(')')) {
                        const start = rawVal.indexOf('(');
                        const end = rawVal.indexOf(')');
                        if (end > start) {
                            slotGrade = rawVal.substring(start + 1, end).trim();
                            subject = rawVal.substring(0, start).trim();
                        }
                    }
                    
                    const selectEl = document.getElementById(`slot-${key}`);
                    const gradeSelect = document.getElementById(`slot-grade-${key}`);
                    if (gradeSelect) gradeSelect.value = slotGrade;
                    
                    // Repopulate subject options if in teacher mode since grade select value might have changed
                    if (timetableType === 'teacher') {
                        let targetGrade = slotGrade;
                        let subjects = [];
                        if (targetGrade) {
                            subjects = subjectsHistoryData.filter(sub => 
                                sub.grade && sub.grade.trim().toLowerCase() === targetGrade.trim().toLowerCase()
                            ).map(sub => sub.subject_name);
                        }
                        subjects = [...new Set(subjects)];
                        let optionsHtml = '<option value="">Select Subject / Free Slot</option>';
                        const commonOptions = ['Interval', 'Break', 'Lunch', 'Assembly'];
                        commonOptions.forEach(opt => { optionsHtml += `<option value="${opt}">${opt}</option>`; });
                        subjects.forEach(subName => {
                            if (!commonOptions.includes(subName)) { optionsHtml += `<option value="${subName}">${subName}</option>`; }
                        });
                        selectEl.innerHTML = optionsHtml;
                    }
                    
                    // Set value
                    if (subject) {
                        const hasOption = Array.from(selectEl.options).some(opt => opt.value === subject);
                        if (!hasOption) {
                            selectEl.innerHTML += `<option value="${subject}">${subject}</option>`;
                        }
                        selectEl.value = subject;
                    } else {
                        selectEl.value = '';
                    }
                });
            }
        }

        // Change select fields reload form
        document.getElementById('timetable-day').addEventListener('change', prefillTimetableForm);
        document.getElementById('timetable-grade').addEventListener('change', () => {
            updateSubjectDropdowns();
            prefillTimetableForm();
        });
        document.getElementById('timetable-teacher').addEventListener('change', prefillTimetableForm);

        // Form Submit
        document.getElementById('timetable-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const day = document.getElementById('timetable-day').value;
            const grade = getSelectedTimetableGrade();
            
            if (!grade) {
                showToast('Please select a grade or teacher.', 'error');
                return;
            }
            
            const payload = { day, grade };
            
            Object.keys(slotsMetadata).forEach(key => {
                const subjectVal = document.getElementById(`slot-${key}`).value.trim();
                if (subjectVal) {
                    if (timetableType === 'teacher') {
                        const gradeVal = document.getElementById(`slot-grade-${key}`).value;
                        if (gradeVal) {
                            payload[key] = `${subjectVal} (${gradeVal})`;
                        } else {
                            payload[key] = subjectVal;
                        }
                    } else {
                        payload[key] = subjectVal;
                    }
                } else {
                    payload[key] = '';
                }
            });
            
            try {
                const res = await apiRequest('/simple-timetables', {
                    method: 'POST',
                    body: JSON.stringify(payload)
                });
                
                // Check if success
                if (res && (res.success || res.id)) {
                    showToast('Timetable saved successfully!', 'success');
                    fetchTimetableRoster(); // Refresh internal roster list
                } else {
                    showToast('Failed to save timetable fields.', 'error');
                }
            } catch (err) {
                showToast('API save error: ' + err.message, 'error');
            }
        });

        // Toast Helper
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const text = document.getElementById('toast-text');
            
            text.innerText = message;
            toast.className = `toast toast-${type} show`;
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // 5. Messaging Hub SPA methods
        let activeChatUserId = null;
        let activeChatUserRole = '';
        let activeChatUserName = '';
        let chatSidebarTab = 'chats'; // 'chats' or 'contacts'
        let chatConversations = [];
        let chatMessagesPollingInterval = null;

        async function fetchConversationsAndContacts() {
            const sidebarList = document.getElementById('chat-sidebar-list');
            sidebarList.innerHTML = '<div style="text-align: center; color: #697386; padding: 20px; font-size: 13px;">Loading...</div>';
            
            try {
                // Fetch recent conversations
                const convRes = await apiRequest('/messages/conversations');
                chatConversations = convRes || [];

                // Fetch all users for contacts directory
                const userRes = await apiRequest('/admin/users');
                if (userRes.success) {
                    // Exclude current logged-in admin from contacts list to prevent self-chatting
                    const currentAdmin = JSON.parse(localStorage.getItem('admin_user') || '{}');
                    usersData = userRes.data.filter(u => u.id !== currentAdmin.id);
                }
                
                renderChatSidebar();
            } catch (err) {
                sidebarList.innerHTML = `<div style="text-align: center; color: #ff4d4f; padding: 20px; font-size: 13px;">Failed to load: ${err.message}</div>`;
            }
        }

        function switchChatTab(tabName) {
            chatSidebarTab = tabName;
            
            document.getElementById('chat-tab-chats').classList.toggle('active', tabName === 'chats');
            document.getElementById('chat-tab-contacts').classList.toggle('active', tabName === 'contacts');
            
            document.getElementById('chat-search').value = '';
            renderChatSidebar();
        }

        function renderChatSidebar() {
            const sidebarList = document.getElementById('chat-sidebar-list');
            const searchVal = document.getElementById('chat-search').value.toLowerCase().trim();
            
            sidebarList.innerHTML = '';
            
            if (chatSidebarTab === 'chats') {
                const filtered = chatConversations.filter(c => {
                    const name = (c.user && c.user.name) ? c.user.name.toLowerCase() : '';
                    return name.includes(searchVal);
                });
                
                if (filtered.length === 0) {
                    sidebarList.innerHTML = '<div style="text-align: center; color: #697386; padding: 20px; font-size: 13px;">No recent chats</div>';
                    return;
                }
                
                filtered.forEach(c => {
                    if (!c.user) return;
                    const isSelected = activeChatUserId === c.user.id;
                    const avatarLetter = c.user.name.substring(0, 1).toUpperCase();
                    
                    // Format time
                    let timeStr = '';
                    if (c.last_message_time) {
                        try {
                            const date = new Date(c.last_message_time);
                            timeStr = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        } catch(e) {}
                    }
                    
                    const itemDiv = document.createElement('div');
                    itemDiv.className = `chat-item ${isSelected ? 'active' : ''}`;
                    itemDiv.setAttribute('onclick', `selectChatUser(${c.user.id}, '${c.user.name.replace(/'/g, "\\'")}', '${c.user.role}')`);
                    
                    itemDiv.innerHTML = `
                        <div class="chat-item-avatar">${avatarLetter}</div>
                        <div class="chat-item-details">
                            <div class="chat-item-header">
                                <span class="chat-item-name">${c.user.name}</span>
                                <span class="chat-item-time">${timeStr}</span>
                            </div>
                            <div class="chat-item-badge-row">
                                <span class="chat-item-preview">${c.last_message || ''}</span>
                                ${c.unread_count > 0 ? `<span class="chat-unread-count">${c.unread_count}</span>` : ''}
                            </div>
                        </div>
                    `;
                    sidebarList.appendChild(itemDiv);
                });
            } else {
                const filtered = usersData.filter(u => {
                    const name = u.name ? u.name.toLowerCase() : '';
                    const email = u.email ? u.email.toLowerCase() : '';
                    return name.includes(searchVal) || email.includes(searchVal);
                });
                
                if (filtered.length === 0) {
                    sidebarList.innerHTML = '<div style="text-align: center; color: #697386; padding: 20px; font-size: 13px;">No contacts found</div>';
                    return;
                }
                
                filtered.forEach(u => {
                    const isSelected = activeChatUserId === u.id;
                    const avatarLetter = u.name.substring(0, 1).toUpperCase();
                    
                    let bgGradient = 'linear-gradient(135deg, #4caf50, #81c784)';
                    if (u.role.toLowerCase() === 'admin') {
                        bgGradient = 'linear-gradient(135deg, #0077be, #00b4db)';
                    } else if (u.role.toLowerCase() === 'teacher') {
                        bgGradient = 'linear-gradient(135deg, #9c27b0, #ba68c8)';
                    }

                    const itemDiv = document.createElement('div');
                    itemDiv.className = `chat-item ${isSelected ? 'active' : ''}`;
                    itemDiv.setAttribute('onclick', `selectChatUser(${u.id}, '${u.name.replace(/'/g, "\\'")}', '${u.role}')`);
                    
                    itemDiv.innerHTML = `
                        <div class="chat-item-avatar" style="background: ${bgGradient};">${avatarLetter}</div>
                        <div class="chat-item-details">
                            <div class="chat-item-header">
                                <span class="chat-item-name">${u.name} ${u.role.toLowerCase() === 'admin' ? '🛡️' : ''}</span>
                            </div>
                            <div class="chat-item-badge-row" style="margin-top: 2px;">
                                <span class="badge badge-${u.role.toLowerCase()}" style="padding: 2px 6px; font-size: 9px;">${u.role.toUpperCase()}</span>
                            </div>
                        </div>
                    `;
                    sidebarList.appendChild(itemDiv);
                });
            }
        }

        function filterChatSidebar() {
            renderChatSidebar();
        }

        function selectChatUser(userId, userName, userRole) {
            activeChatUserId = userId;
            activeChatUserName = userName;
            activeChatUserRole = userRole;
            
            // Highlight selected in sidebar
            const items = document.querySelectorAll('.chat-item');
            items.forEach(el => el.classList.remove('active'));
            
            // Update active states
            renderChatSidebar();
            
            // Toggle view area
            document.getElementById('chat-area-empty').classList.add('hidden');
            document.getElementById('chat-area-active').classList.remove('hidden');
            
            // Set details
            document.getElementById('active-chat-avatar').innerText = userName.substring(0, 1).toUpperCase();
            document.getElementById('active-chat-name').innerText = userName;
            
            const badge = document.getElementById('active-chat-badge');
            badge.innerText = userRole.toUpperCase();
            badge.className = `badge badge-${userRole.toLowerCase()}`;
            
            // Load messages
            const msgBox = document.getElementById('chat-messages-box');
            msgBox.innerHTML = '<div style="text-align: center; color: #697386; padding: 20px; font-size: 13px;">Loading messages...</div>';
            
            loadActiveMessages(true);
            
            // Setup polling interval (3 seconds)
            if (chatMessagesPollingInterval) {
                clearInterval(chatMessagesPollingInterval);
            }
            chatMessagesPollingInterval = setInterval(() => {
                loadActiveMessages(false);
            }, 3000);
        }

        async function loadActiveMessages(shouldScroll = false) {
            if (!activeChatUserId) return;
            
            try {
                const messages = await apiRequest(`/messages/with/${activeChatUserId}`);
                const msgBox = document.getElementById('chat-messages-box');
                const currentUser = JSON.parse(localStorage.getItem('admin_user') || '{}');
                
                if (!messages || !Array.isArray(messages)) {
                    return;
                }
                
                // Compare count of rendered items or update HTML directly
                const previousHTML = msgBox.innerHTML;
                
                let html = '';
                if (messages.length === 0) {
                    html = '<div style="text-align: center; color: #697386; padding: 40px; font-size: 13px; font-style: italic;">No messages yet. Say hello!</div>';
                } else {
                    html = messages.map(msg => {
                        const isSent = msg.sender_id == currentUser.id;
                        let timeStr = '';
                        if (msg.created_at) {
                            try {
                                const date = new Date(msg.created_at);
                                timeStr = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                            } catch(e) {}
                        }
                        
                        return `
                            <div class="message-bubble ${isSent ? 'message-sent' : 'message-received'}">
                                <div class="message-content">${msg.content}</div>
                                <div class="message-time">${timeStr}</div>
                            </div>
                        `;
                    }).join('');
                }
                
                if (previousHTML !== html) {
                    msgBox.innerHTML = html;
                    if (shouldScroll || msgBox.scrollTop + msgBox.clientHeight >= msgBox.scrollHeight - 100) {
                        msgBox.scrollTop = msgBox.scrollHeight;
                    }
                }
            } catch (err) {
                console.error("Failed to load active messages:", err);
            }
        }

        async function sendChatMessage(e) {
            e.preventDefault();
            if (!activeChatUserId) return;
            
            const inputEl = document.getElementById('chat-input-text');
            const content = inputEl.value.trim();
            if (!content) return;
            
            inputEl.value = '';
            
            try {
                const res = await apiRequest('/messages', {
                    method: 'POST',
                    body: JSON.stringify({
                        receiver_id: activeChatUserId,
                        content: content
                    })
                });
                
                if (res && res.data) {
                    // Instantly render sent message for smoothness
                    loadActiveMessages(true);
                    // Refresh sidebar counts/snippets
                    const convRes = await apiRequest('/messages/conversations');
                    chatConversations = convRes || [];
                    renderChatSidebar();
                } else {
                    showToast('Failed to send message.', 'error');
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        let activeModalStudentId = null;

        async function openFeesModal(studentId, studentName) {
            activeModalStudentId = studentId;
            document.getElementById('fees-student-name').innerText = studentName;
            document.getElementById('fees-list-container').innerHTML = '<div style="text-align: center; color: #697386; padding: 20px;">Loading fees info...</div>';
            
            document.getElementById('fees-modal').classList.remove('hidden');
            await loadFeesModalData(studentId);
        }

        function closeFeesModal() {
            document.getElementById('fees-modal').classList.add('hidden');
            activeModalStudentId = null;
        }

        async function loadFeesModalData(studentId) {
            try {
                const res = await apiRequest(`/fees?student_id=${studentId}`);
                const container = document.getElementById('fees-list-container');
                container.innerHTML = '';
                
                if (!res || !Array.isArray(res)) {
                    container.innerHTML = '<div style="text-align: center; color: #ff4d4f; padding: 20px;">Failed to load fees data.</div>';
                    return;
                }
                
                if (res.length === 0) {
                    container.innerHTML = '<div style="text-align: center; color: #697386; padding: 20px;">No tuition fees configured.</div>';
                    return;
                }
                
                res.forEach(fee => {
                    const isPaid = fee.payment_status === 'completed';
                    const badgeClass = isPaid ? 'badge-completed' : 'badge-pending';
                    const badgeText = isPaid ? 'PAID' : 'UNPAID';
                    
                    const btnClass = isPaid ? 'unpay' : 'pay';
                    const btnText = isPaid ? 'Mark Unpaid' : 'Mark Paid';
                    
                    const feeItem = document.createElement('div');
                    feeItem.className = 'fee-row-item';
                    feeItem.innerHTML = `
                        <div class="fee-info-col">
                            <span class="fee-row-title">${fee.title}</span>
                            <span class="fee-row-meta">Amount: LKR ${parseFloat(fee.amount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})} • Due: ${fee.due_date}</span>
                        </div>
                        <div class="fee-action-col">
                            <span class="badge ${badgeClass}">${badgeText}</span>
                            <button class="btn-toggle-fee ${btnClass}" onclick="toggleFeeStatus(${studentId}, ${fee.id})">${btnText}</button>
                        </div>
                    `;
                    container.appendChild(feeItem);
                });
            } catch (err) {
                console.error("Error loading fees info:", err);
                document.getElementById('fees-list-container').innerHTML = `<div style="text-align: center; color: #ff4d4f; padding: 20px;">Error: ${err.message}</div>`;
            }
        }


        let feesMonthlyData = []; // raw data from last API call
        let activeFeesTab = 'students';
        let activeMonthlyFee = null;

        function startEditFeeAmount() {
            if (!activeMonthlyFee) {
                showToast('No active fee found for this month.', 'error');
                return;
            }
            document.getElementById('fees-amount-container').style.display = 'none';
            
            const editContainer = document.getElementById('fees-amount-edit-container');
            const input = document.getElementById('fees-amount-input');
            
            input.value = activeMonthlyFee.amount;
            editContainer.style.display = 'inline-flex';
            input.focus();
        }

        function cancelFeeAmountInline() {
            document.getElementById('fees-amount-edit-container').style.display = 'none';
            document.getElementById('fees-amount-container').style.display = 'flex';
        }

        async function saveFeeAmountInline() {
            if (!activeMonthlyFee) return;
            const inputVal = document.getElementById('fees-amount-input').value;
            const newAmount = parseFloat(inputVal);
            if (isNaN(newAmount) || newAmount < 0) {
                showToast('Please enter a valid positive number.', 'error');
                return;
            }

            try {
                const res = await apiRequest(`/fees/${activeMonthlyFee.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ amount: newAmount })
                });

                if (res && res.message) {
                    showToast('Fee amount updated successfully.', 'success');
                    cancelFeeAmountInline();
                    await applyFeesFilter();
                } else {
                    showToast(res.error || 'Failed to update fee amount.', 'error');
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        function switchFeesTab(tab) {
            activeFeesTab = tab;
            const studentTabBtn = document.getElementById('fees-tab-students');
            const teacherTabBtn = document.getElementById('fees-tab-teachers');
            const studentPane = document.getElementById('fees-students-pane');
            const teacherPane = document.getElementById('fees-teachers-pane');

            if (tab === 'students') {
                if (studentTabBtn) {
                    studentTabBtn.style.background = '#0077be';
                    studentTabBtn.style.color = 'white';
                }
                if (teacherTabBtn) {
                    teacherTabBtn.style.background = 'transparent';
                    teacherTabBtn.style.color = '#697386';
                }
                if (studentPane) studentPane.classList.remove('hidden');
                if (teacherPane) teacherPane.classList.add('hidden');
                fetchFeesStudentList();
            } else {
                if (teacherTabBtn) {
                    teacherTabBtn.style.background = '#0077be';
                    teacherTabBtn.style.color = 'white';
                }
                if (studentTabBtn) {
                    studentTabBtn.style.background = 'transparent';
                    studentTabBtn.style.color = '#697386';
                }
                if (studentPane) studentPane.classList.add('hidden');
                if (teacherPane) teacherPane.classList.remove('hidden');
                fetchSalariesList();
            }
        }

        // Build month dropdown (Jan of current year → next month)
        function buildMonthDropdown() {
            const sel = document.getElementById('fees-month-filter');
            if (!sel || sel.options.length > 0) return; // already built

            const now = new Date();
            const year = now.getFullYear();
            const maxMonth = now.getMonth() + 2; // include next month

            const months = ['January','February','March','April','May','June',
                            'July','August','September','October','November','December'];

            for (let m = 1; m <= Math.min(maxMonth, 12); m++) {
                const opt = document.createElement('option');
                opt.value = `${m}|${year}`;
                opt.textContent = `${months[m-1]} ${year}`;
                if (m === now.getMonth() + 1) opt.selected = true; // default = current month
                sel.appendChild(opt);
            }
        }

        // Called when the fees view is activated
        async function fetchFeesStudentList() {
            buildMonthDropdown();
            await applyFeesFilter();
        }

        // Called on filter change or search input
        async function applyFeesFilter() {
            const tbody = document.getElementById('fees-students-table-body');
            const summaryBar = document.getElementById('fees-summary-bar');
            tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;color:#697386;padding:30px;">Loading...</td></tr>';

            const sel = document.getElementById('fees-month-filter');
            if (!sel || !sel.value) return;

            const [month, year]  = sel.value.split('|');
            const statusFilter   = document.getElementById('fees-status-filter')?.value || 'all';

            try {
                const res = await apiRequest(
                    `/admin/fees/monthly-status?month=${month}&year=${year}&status=${statusFilter}`
                );

                if (res && res.success) {
                    feesMonthlyData = res.data || [];

                    // Update summary bar
                    const allForMonth = res.data || [];
                    const paidCount   = allForMonth.filter(s => s.payment_status === 'paid').length;
                    const unpaidCount = allForMonth.filter(s => s.payment_status === 'unpaid').length;

                    document.getElementById('fees-month-label').textContent = res.month_name || '—';
                    document.getElementById('fees-due-label').textContent   = res.fee ? res.fee.due_date : 'No fee set';
                    document.getElementById('fees-amount-label').textContent = res.fee
                        ? `LKR ${parseFloat(res.fee.amount).toLocaleString('en-US', {minimumFractionDigits:2})}`
                        : '—';
                    document.getElementById('fees-paid-count').textContent   = paidCount;
                    document.getElementById('fees-unpaid-count').textContent  = unpaidCount;
                    
                    if (res.fee) {
                        activeMonthlyFee = res.fee;
                        document.getElementById('fees-amount-edit-btn').style.display = 'inline-flex';
                    } else {
                        activeMonthlyFee = null;
                        document.getElementById('fees-amount-edit-btn').style.display = 'none';
                    }

                    summaryBar.style.display = 'flex';

                    renderFeesMonthlyTable();
                } else {
                    tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;color:#ff4d4f;padding:30px;">Failed to load data.</td></tr>';
                    summaryBar.style.display = 'none';
                }
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;color:#ff4d4f;padding:30px;">Error: ${err.message}</td></tr>`;
                summaryBar.style.display = 'none';
            }
        }

        function renderFeesMonthlyTable() {
            const tbody  = document.getElementById('fees-students-table-body');
            const search = (document.getElementById('fees-students-search')?.value || '').toLowerCase();

            const filtered = feesMonthlyData.filter(s =>
                s.name.toLowerCase().includes(search) ||
                s.email.toLowerCase().includes(search)
            );

            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;color:#697386;padding:30px;">No students match this filter.</td></tr>';
                return;
            }

            tbody.innerHTML = filtered.map(s => {
                const isPaid = s.payment_status === 'paid';
                const badgeHtml = isPaid
                    ? `<span class="badge badge-completed">✅ Paid</span>`
                    : `<span class="badge badge-pending">❌ Unpaid</span>`;

                const toggleBtn = s.fee_id
                    ? `<button class="btn-toggle-fee ${isPaid ? 'unpay' : 'pay'}"
                            onclick="toggleFeeStatus(${s.student_id}, ${s.fee_id})"
                            style="padding:4px 12px;font-size:11px;width:auto;margin-top:0;">
                            ${isPaid ? 'Mark Unpaid' : 'Mark Paid'}
                       </button>`
                    : `<span style="color:#697386;font-size:12px;">No fee created</span>`;

                const safeName = s.name.replace(/'/g, "\\'");
                return `
                    <tr>
                        <td style="font-weight:600;">${s.name}</td>
                        <td>${s.email}</td>
                        <td>${s.grade || '—'}</td>
                        <td>${badgeHtml}</td>
                        <td style="display:flex;gap:6px;align-items:center;">
                            ${toggleBtn}
                            <button class="btn-toggle-fee pay"
                                onclick="openFeesModal(${s.student_id}, '${safeName}')"
                                style="padding:4px 10px;font-size:11px;width:auto;margin-top:0;background:#0077be;">
                                All Fees
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Override toggleFeeStatus to refresh the monthly table after toggling
        const _origToggle = typeof toggleFeeStatus === 'function' ? toggleFeeStatus : null;
        async function toggleFeeStatus(studentId, feeId) {
            try {
                const res = await apiRequest(`/admin/students/${studentId}/toggle-fee/${feeId}`, { method: 'POST' });
                if (res && res.success) {
                    showToast(res.message, 'success');
                    // If we are in the fees view, refresh the monthly table
                    if (currentView === 'fees') {
                        await applyFeesFilter();
                    }
                    if (activeModalStudentId === studentId) {
                        await loadFeesModalData(studentId);
                    }
                } else {
                    showToast(res.error || 'Failed to update fee status.', 'error');
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        // ─── Teacher Salaries View ───────────────────────────────────────────
        let salariesMonthlyData = [];

        function buildSalariesMonthDropdown() {
            const sel = document.getElementById('salaries-month-filter');
            if (!sel || sel.options.length > 0) return;

            const now = new Date();
            const year = now.getFullYear();
            const maxMonth = now.getMonth() + 2; // current + next month preview

            const months = ['January','February','March','April','May','June',
                            'July','August','September','October','November','December'];

            for (let m = 1; m <= Math.min(maxMonth, 12); m++) {
                const opt = document.createElement('option');
                opt.value = `${m}|${year}`;
                opt.textContent = `${months[m-1]} ${year}`;
                if (m === now.getMonth() + 1) opt.selected = true;
                sel.appendChild(opt);
            }
        }

        async function fetchSalariesList() {
            buildSalariesMonthDropdown();
            await applySalariesFilter();
        }

        async function applySalariesFilter() {
            const tbody = document.getElementById('salaries-table-body');
            const summaryBar = document.getElementById('salaries-summary-bar');
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:#697386;padding:30px;">Loading salaries...</td></tr>';

            const sel = document.getElementById('salaries-month-filter');
            if (!sel || !sel.value) return;

            const [month, year] = sel.value.split('|');
            const statusFilter = document.getElementById('salaries-status-filter')?.value || 'all';

            try {
                const res = await apiRequest(
                    `/admin/salaries/monthly-status?month=${month}&year=${year}&status=${statusFilter}`
                );

                if (res && res.success) {
                    salariesMonthlyData = res.data || [];

                    // Compute summary metrics (based on ALL fetched teachers for this month)
                    let totalPayroll = 0;
                    let paidAmount = 0;
                    let unpaidAmount = 0;
                    let paidCount = 0;
                    let unpaidCount = 0;

                    salariesMonthlyData.forEach(t => {
                        const salary = parseFloat(t.basic_salary) || 0;
                        totalPayroll += salary;
                        if (t.payment_status === 'paid') {
                            paidCount++;
                            paidAmount += parseFloat(t.payment_details?.amount || salary);
                        } else {
                            unpaidCount++;
                            unpaidAmount += salary;
                        }
                    });

                    document.getElementById('salaries-month-label').textContent = res.month_name;
                    document.getElementById('salaries-total-label').textContent = `LKR ${totalPayroll.toLocaleString('en-US', {minimumFractionDigits:2})}`;
                    document.getElementById('salaries-paid-amount').textContent = `LKR ${paidAmount.toLocaleString('en-US', {minimumFractionDigits:2})}`;
                    document.getElementById('salaries-unpaid-amount').textContent = `LKR ${unpaidAmount.toLocaleString('en-US', {minimumFractionDigits:2})}`;
                    document.getElementById('salaries-staff-counts').textContent = `${paidCount} paid / ${unpaidCount} unpaid`;
                    summaryBar.style.display = 'flex';

                    renderSalariesMonthlyTable();
                } else {
                    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:#ff4d4f;padding:30px;">Failed to load salaries data.</td></tr>';
                    summaryBar.style.display = 'none';
                }
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;color:#ff4d4f;padding:30px;">Error: ${err.message}</td></tr>`;
                summaryBar.style.display = 'none';
            }
        }

        function renderSalariesMonthlyTable() {
            const tbody = document.getElementById('salaries-table-body');
            const search = (document.getElementById('salaries-teachers-search')?.value || '').toLowerCase();

            const filtered = salariesMonthlyData.filter(t =>
                t.name.toLowerCase().includes(search) ||
                (t.specialization || '').toLowerCase().includes(search)
            );

            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:#697386;padding:30px;">No teachers match current filters.</td></tr>';
                return;
            }

            tbody.innerHTML = filtered.map(t => {
                const isPaid = t.payment_status === 'paid';
                const badgeHtml = isPaid
                    ? `<span class="badge badge-completed">✅ Paid</span>`
                    : `<span class="badge badge-pending">❌ Unpaid</span>`;

                const actionBtn = isPaid
                    ? `<button class="btn-toggle-fee unpay" onclick="toggleSalaryStatusDirect(${t.teacher_id})"
                            style="padding:4px 12px;font-size:11px;width:auto;margin-top:0;">
                            Mark Unpaid
                       </button>`
                    : `<button class="btn-toggle-fee pay" onclick="openSalaryModal(${t.teacher_id}, '${t.name.replace(/'/g, "\\'")}', ${t.basic_salary})"
                            style="padding:4px 12px;font-size:11px;width:auto;margin-top:0;background:#0077be;">
                            Pay Salary
                       </button>`;

                const salaryFormatted = `LKR ${parseFloat(t.basic_salary).toLocaleString('en-US', {minimumFractionDigits:2})}`;

                return `
                    <tr>
                        <td style="font-weight:600;">${t.name}</td>
                        <td>${t.employee_id || '—'}</td>
                        <td>${t.specialization || '—'}</td>
                        <td>
                            <div id="salary-display-${t.teacher_id}" style="display:flex;align-items:center;gap:6px;">
                                <span style="font-weight:600;">${salaryFormatted}</span>
                                <button type="button"
                                    onclick="startEditSalary(${t.teacher_id}, ${t.basic_salary})"
                                    style="background:none;border:none;color:#0077be;cursor:pointer;padding:2px;display:inline-flex;align-items:center;"
                                    title="Edit Basic Salary">
                                    <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                    </svg>
                                </button>
                            </div>
                            <div id="salary-edit-${t.teacher_id}" style="display:none;align-items:center;gap:4px;">
                                <input type="number" id="salary-input-${t.teacher_id}" step="0.01" min="0"
                                    style="width:90px;padding:2px 6px;border:1px solid #0077be;border-radius:4px;font-size:12px;font-weight:600;outline:none;">
                                <button type="button" onclick="saveSalaryInline(${t.teacher_id})"
                                    style="background:#0077be;border:none;color:white;padding:2px 7px;border-radius:4px;font-size:11px;cursor:pointer;font-weight:600;">Save</button>
                                <button type="button" onclick="cancelSalaryInline(${t.teacher_id})"
                                    style="background:#697386;border:none;color:white;padding:2px 7px;border-radius:4px;font-size:11px;cursor:pointer;font-weight:600;">Cancel</button>
                            </div>
                        </td>
                        <td>${badgeHtml}</td>
                        <td>${actionBtn}</td>
                    </tr>
                `;
            }).join('');
        }

        function startEditSalary(teacherId, currentSalary) {
            document.getElementById(`salary-display-${teacherId}`).style.display = 'none';
            const editDiv = document.getElementById(`salary-edit-${teacherId}`);
            const input = document.getElementById(`salary-input-${teacherId}`);
            input.value = currentSalary;
            editDiv.style.display = 'inline-flex';
            input.focus();
        }

        function cancelSalaryInline(teacherId) {
            document.getElementById(`salary-edit-${teacherId}`).style.display = 'none';
            document.getElementById(`salary-display-${teacherId}`).style.display = 'flex';
        }

        async function saveSalaryInline(teacherId) {
            const inputEl = document.getElementById(`salary-input-${teacherId}`);
            const newSalary = parseFloat(inputEl.value);
            if (isNaN(newSalary) || newSalary < 0) {
                showToast('Please enter a valid positive salary amount.', 'error');
                return;
            }
            try {
                const res = await apiRequest('/admin/salaries/basic-salary', {
                    method: 'PUT',
                    body: JSON.stringify({ teacher_id: teacherId, salary: newSalary })
                });
                if (res && res.success) {
                    showToast('Basic salary updated successfully.', 'success');
                    // Update local data and re-render
                    const rec = salariesMonthlyData.find(t => t.teacher_id === teacherId);
                    if (rec) rec.basic_salary = newSalary;
                    renderSalariesMonthlyTable();
                } else {
                    showToast((res.errors ? Object.values(res.errors).flat().join(', ') : res.message) || 'Failed to update salary.', 'error');
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        function openSalaryModal(teacherId, name, basicSalary) {
            const sel = document.getElementById('salaries-month-filter');
            if (!sel) return;

            const [month, year] = sel.value.split('|');
            const months = ['January','February','March','April','May','June',
                            'July','August','September','October','November','December'];

            document.getElementById('salary-teacher-id').value = teacherId;
            document.getElementById('salary-month').value = month;
            document.getElementById('salary-year').value = year;
            document.getElementById('salary-teacher-name').value = name;
            document.getElementById('salary-period').value = `${months[parseInt(month)-1]} ${year}`;
            document.getElementById('salary-amount').value = parseFloat(basicSalary) || 0.00;
            document.getElementById('salary-method').value = 'bank_transfer';
            document.getElementById('salary-notes').value = '';

            document.getElementById('salary-modal').classList.remove('hidden');
        }

        function closeSalaryModal() {
            document.getElementById('salary-modal').classList.add('hidden');
        }

        async function submitSalaryPayment(event) {
            event.preventDefault();

            const teacherId = document.getElementById('salary-teacher-id').value;
            const month = document.getElementById('salary-month').value;
            const year = document.getElementById('salary-year').value;
            const amount = document.getElementById('salary-amount').value;
            const method = document.getElementById('salary-method').value;
            const notes = document.getElementById('salary-notes').value;

            try {
                const res = await apiRequest('/admin/salaries/toggle', {
                    method: 'POST',
                    body: JSON.stringify({
                        teacher_id: teacherId,
                        month: month,
                        year: year,
                        amount: amount,
                        payment_method: method,
                        notes: notes
                    })
                });

                if (res && res.success) {
                    showToast(res.message, 'success');
                    closeSalaryModal();
                    await applySalariesFilter();
                } else {
                    showToast(res.error || 'Failed to record salary payment.', 'error');
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        async function toggleSalaryStatusDirect(teacherId) {
            const sel = document.getElementById('salaries-month-filter');
            if (!sel) return;

            const [month, year] = sel.value.split('|');

            if (!confirm('Are you sure you want to mark this salary payment as unpaid? This will delete the payment record.')) {
                return;
            }

            try {
                const res = await apiRequest('/admin/salaries/toggle', {
                    method: 'POST',
                    body: JSON.stringify({
                        teacher_id: teacherId,
                        month: month,
                        year: year
                    })
                });

                if (res && res.success) {
                    showToast(res.message, 'success');
                    await applySalariesFilter();
                } else {
                    showToast(res.error || 'Failed to update salary status.', 'error');
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        // Emergency Broadcast & Sub-tab Handlers
        function switchEmergencySubTab(subTab) {
            const triggerTab = document.getElementById('emergency-subtab-trigger');
            const historyTab = document.getElementById('emergency-subtab-history');
            const triggerBtn = document.getElementById('emergency-tab-trigger-btn');
            const historyBtn = document.getElementById('emergency-tab-history-btn');

            if (subTab === 'trigger') {
                triggerTab?.classList.remove('hidden');
                historyTab?.classList.add('hidden');
                if (triggerBtn) {
                    triggerBtn.style.background = '#ff4d4f';
                    triggerBtn.style.color = 'white';
                }
                if (historyBtn) {
                    historyBtn.style.background = 'rgba(0,0,0,0.05)';
                    historyBtn.style.color = '#1a1f36';
                }
            } else {
                triggerTab?.classList.add('hidden');
                historyTab?.classList.remove('hidden');
                if (historyBtn) {
                    historyBtn.style.background = '#ff4d4f';
                    historyBtn.style.color = 'white';
                }
                if (triggerBtn) {
                    triggerBtn.style.background = 'rgba(0,0,0,0.05)';
                    triggerBtn.style.color = '#1a1f36';
                }
                fetchEmergencyAlertsHistory();
            }
        }

        async function fetchEmergencyAlertsHistory() {
            const tbody = document.getElementById('emergency-history-table-body');
            if (!tbody) return;
            tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: #697386; padding: 30px;">Loading emergency history...</td></tr>';
            try {
                const data = await apiRequest('/emergency-alerts');
                if (Array.isArray(data)) {
                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: #697386; padding: 30px;">No emergency alerts broadcasted yet.</td></tr>';
                        return;
                    }
                    tbody.innerHTML = data.map(item => {
                        const dateStr = new Date(item.created_at).toLocaleString();
                        const sender = item.posted_by ? `${item.posted_by.name} (${item.posted_by.role.toUpperCase()})` : 'System';
                        const presetBadge = `<span class="badge" style="background:rgba(255,77,79,0.1); color:#ff4d4f; font-weight:700;">${(item.preset_type || 'custom').toUpperCase()}</span>`;
                        return `
                            <tr>
                                <td style="font-weight:600; font-size:13px; color:#555;">${dateStr}</td>
                                <td style="font-weight:700; color:#ff4d4f;">${item.title}</td>
                                <td>${item.content}</td>
                                <td>${presetBadge}</td>
                                <td style="font-weight:600;">${sender}</td>
                            </tr>
                        `;
                    }).join('');
                }
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="5" style="text-align: center; color: #ff4d4f; padding: 30px;">Failed to load history: ${err.message}</td></tr>`;
            }
        }

        async function triggerPresetEmergency(presetType) {
            const presets = {
                'fire': {
                    title: '🔥 FIRE EVACUATION ALARM',
                    content: 'A Fire Emergency has been reported on school grounds! All students, teachers, and staff must evacuate immediately using the nearest emergency exit to safe assembly points.'
                },
                'weather': {
                    title: '⛈️ SEVERE WEATHER EMERGENCY',
                    content: 'A Severe Weather & Heavy Storm alert has been issued. All students and staff must remain indoors in designated safe shelter rooms until further notice.'
                },
                'lockdown': {
                    title: '🔒 CAMPUS SECURITY LOCKDOWN',
                    content: 'Immediate Campus Security Lockdown is in effect. All classroom doors must be locked. Remain indoors, keep away from windows, and await instructions.'
                }
            };

            const selected = presets[presetType];
            if (!selected) return;

            if (confirm(`🚨 ARE YOU SURE YOU WANT TO BROADCAST THIS EMERGENCY ALERT?\n\n"${selected.title}"\n\nThis will send a high-priority push notification to ALL students and teachers immediately!`)) {
                await sendEmergencyApiCall(selected.title, selected.content, presetType);
            }
        }

        async function handleSendEmergencyAlert(event) {
            event.preventDefault();
            const title = document.getElementById('emergency-title').value;
            const content = document.getElementById('emergency-content').value;

            if (confirm(`🚨 ARE YOU SURE YOU WANT TO BROADCAST THIS CUSTOM EMERGENCY ALERT?\n\n"${title}"\n\nThis will trigger push notifications to ALL phones!`)) {
                await sendEmergencyApiCall(title, content, 'custom');
            }
        }

        async function sendEmergencyApiCall(title, content, preset_type = 'custom') {
            const submitBtn = document.getElementById('emergency-submit-btn');
            if (submitBtn) submitBtn.disabled = true;
            try {
                const res = await apiRequest('/emergency-alert', {
                    method: 'POST',
                    body: JSON.stringify({ title, content, preset_type })
                });
                showToast(res.message || '🚨 Emergency alert broadcasted successfully!', 'success');
                document.getElementById('emergency-form')?.reset();
            } catch (err) {
                showToast(err.message || 'Failed to send emergency alert.', 'error');
            } finally {
                if (submitBtn) submitBtn.disabled = false;
            }
        }

        // Notices State & Handlers
        let noticesData = [];

        async function fetchNotices() {
            const tbody = document.getElementById('notices-table-body');
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #697386; padding: 30px;">Loading notices...</td></tr>';
            try {
                const data = await apiRequest('/notices');
                if (Array.isArray(data)) {
                    noticesData = data;
                    renderNoticesTable();
                } else {
                    tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #ff4d4f; padding: 30px;">Failed to load notices.</td></tr>';
                }
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: #ff4d4f; padding: 30px;">Error: ${err.message}</td></tr>`;
            }
        }

        function renderNoticesTable() {
            const tbody = document.getElementById('notices-table-body');
            const search = (document.getElementById('notices-search')?.value || '').toLowerCase();
            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const role = ((adminObj && adminObj.role) || localStorage.getItem('user_role') || 'admin').toLowerCase();

            // Hide Create Notice button and Actions for Teachers
            const createBtn = document.getElementById('btn-create-notice');
            if (createBtn) {
                createBtn.style.display = (role === 'teacher') ? 'none' : 'inline-flex';
            }
            const thActions = document.getElementById('th-notice-actions');
            if (thActions) {
                thActions.style.display = (role === 'teacher') ? 'none' : '';
            }

            const filtered = noticesData.filter(notice => 
                notice.title.toLowerCase().includes(search) || 
                (notice.content || '').toLowerCase().includes(search) ||
                notice.category.toLowerCase().includes(search)
            );

            if (filtered.length === 0) {
                tbody.innerHTML = `<tr><td colspan="${role === 'teacher' ? 5 : 6}" style="text-align: center; color: #697386; padding: 30px;">No notices found.</td></tr>`;
                return;
            }

            tbody.innerHTML = filtered.map(notice => {
                const audienceLabel = {
                    'all': '<span class="badge badge-completed" style="background:#0077be; color:white;">All</span>',
                    'students': '<span class="badge badge-completed" style="background:#4caf50; color:white;">Students Only</span>',
                    'teachers': '<span class="badge badge-completed" style="background:#9c27b0; color:white;">Teachers Only</span>',
                    'individual': '<span class="badge badge-completed" style="background:#ff9800; color:white;">Individual</span>'
                }[notice.target_audience] || notice.target_audience;

                const recipientName = notice.recipient ? `${notice.recipient.name} (${notice.recipient.role})` : '—';
                const expiry = notice.expiry_date ? new Date(notice.expiry_date).toLocaleDateString() : 'Never';
                const categoryBadge = `<span class="badge" style="background:rgba(0,0,0,0.05); color:#333;">${notice.category.toUpperCase()}</span>`;

                const actionTd = role === 'teacher' ? '' : `
                    <td style="display:flex; gap:6px; align-items:center;">
                        <button class="btn-toggle-fee pay" onclick="openEditNoticeModal(${notice.id})"
                            style="padding:4px 12px; font-size:11px; width:auto; margin-top:0; background:#0077be;">
                            Edit
                        </button>
                        <button class="btn-toggle-fee unpay" onclick="deleteNotice(${notice.id})" 
                            style="padding:4px 12px; font-size:11px; width:auto; margin-top:0;">
                            Delete
                        </button>
                    </td>
                `;

                return `
                    <tr>
                        <td style="font-weight:600; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${notice.title}</td>
                        <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${notice.content}</td>
                        <td>${categoryBadge}</td>
                        <td>${audienceLabel}</td>
                        <td>${expiry}</td>
                        ${actionTd}
                    </tr>
                `;
            }).join('');
        }

        async function deleteNotice(id) {
            if (!confirm('Are you sure you want to delete this notice?')) {
                return;
            }
            try {
                const res = await apiRequest(`/notices/${id}`, {
                    method: 'DELETE'
                });
                showToast('Notice deleted successfully', 'success');
                await fetchNotices();
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        async function _loadNoticeRecipients() {
            const recipientSelect = document.getElementById('notice-recipient');
            recipientSelect.innerHTML = '<option value="">Select recipient...</option>';
            try {
                const res = await apiRequest('/admin/users');
                if (res.success && Array.isArray(res.data)) {
                    const validUsers = res.data.filter(u => u.role === 'student' || u.role === 'teacher' || u.role === 'admin');
                    validUsers.forEach(u => {
                        const opt = document.createElement('option');
                        opt.value = u.id;
                        opt.textContent = `${u.name} (${u.role.toUpperCase()})`;
                        recipientSelect.appendChild(opt);
                    });
                }
            } catch (err) {
                console.error('Failed to load users for notice recipient:', err);
            }
        }

        async function openCreateNoticeModal() {
            document.getElementById('notice-form').reset();
            document.getElementById('notice-edit-id').value = '';
            document.getElementById('notice-modal-title').textContent = 'Create New Notice';
            document.getElementById('notice-submit-btn').textContent = 'Publish Notice';
            document.getElementById('notice-recipient-group').style.display = 'none';
            document.getElementById('notice-modal').classList.remove('hidden');
            await _loadNoticeRecipients();
        }

        async function openEditNoticeModal(id) {
            const notice = noticesData.find(n => n.id === id);
            if (!notice) return;

            document.getElementById('notice-form').reset();
            document.getElementById('notice-edit-id').value = id;
            document.getElementById('notice-modal-title').textContent = 'Edit Notice';
            document.getElementById('notice-submit-btn').textContent = 'Save Changes';

            // Pre-fill fields
            document.getElementById('notice-title').value = notice.title || '';
            document.getElementById('notice-content').value = notice.content || '';
            document.getElementById('notice-category').value = notice.category || 'academic';
            document.getElementById('notice-audience').value = notice.target_audience || 'all';
            document.getElementById('notice-expiry').value = notice.expiry_date
                ? notice.expiry_date.substring(0, 10) : '';

            toggleNoticeRecipientSelect();

            await _loadNoticeRecipients();

            if (notice.recipient_id) {
                document.getElementById('notice-recipient').value = notice.recipient_id;
            }

            document.getElementById('notice-modal').classList.remove('hidden');
        }

        function closeNoticeModal() {
            document.getElementById('notice-modal').classList.add('hidden');
        }

        function toggleNoticeRecipientSelect() {
            const audience = document.getElementById('notice-audience').value;
            const group = document.getElementById('notice-recipient-group');
            const recipientSelect = document.getElementById('notice-recipient');

            if (audience === 'individual') {
                group.style.display = 'block';
                recipientSelect.setAttribute('required', 'required');
            } else {
                group.style.display = 'none';
                recipientSelect.removeAttribute('required');
            }
        }

        async function handleCreateNotice(event) {
            event.preventDefault();

            const editId = document.getElementById('notice-edit-id').value;
            const title = document.getElementById('notice-title').value.trim();
            const content = document.getElementById('notice-content').value.trim();
            const category = document.getElementById('notice-category').value;
            const target_audience = document.getElementById('notice-audience').value;
            const recipient_id = document.getElementById('notice-recipient').value || null;
            const expiry_date = document.getElementById('notice-expiry').value || null;

            const payload = { title, content, category, target_audience, recipient_id, expiry_date };

            try {
                if (editId) {
                    // Edit mode → PUT
                    await apiRequest(`/notices/${editId}`, {
                        method: 'PUT',
                        body: JSON.stringify(payload)
                    });
                    showToast('Notice updated successfully', 'success');
                } else {
                    // Create mode → POST
                    await apiRequest('/notices', {
                        method: 'POST',
                        body: JSON.stringify(payload)
                    });
                    showToast('Notice published successfully', 'success');
                }
                closeNoticeModal();
                await fetchNotices();
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        // Events State & Handlers
        let eventsData = [];

        async function fetchEvents() {
            const tbody = document.getElementById('events-table-body');
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #697386; padding: 30px;">Loading events...</td></tr>';
            try {
                const data = await apiRequest('/events');
                if (Array.isArray(data)) {
                    eventsData = data;
                    renderEventsTable();
                } else {
                    tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #ff4d4f; padding: 30px;">Failed to load events.</td></tr>';
                }
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: #ff4d4f; padding: 30px;">Error: ${err.message}</td></tr>`;
            }
        }

        function formatEventTime(timeStr) {
            if (!timeStr) return '—';
            if (timeStr.includes('T')) {
                const parts = timeStr.split('T')[1].split(':');
                return `${parts[0]}:${parts[1]}`;
            }
            if (timeStr.includes(' ')) {
                const parts = timeStr.split(' ')[1].split(':');
                return `${parts[0]}:${parts[1]}`;
            }
            const parts = timeStr.split(':');
            if (parts.length >= 2) {
                return `${parts[0]}:${parts[1]}`;
            }
            return timeStr;
        }

        function renderEventsTable() {
            const tbody = document.getElementById('events-table-body');
            const search = (document.getElementById('events-search')?.value || '').toLowerCase();
            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const role = ((adminObj && adminObj.role) || localStorage.getItem('user_role') || 'admin').toLowerCase();

            // Hide Create Event button and Actions for Teachers
            const createBtn = document.getElementById('btn-create-event');
            if (createBtn) {
                createBtn.style.display = (role === 'teacher') ? 'none' : 'inline-flex';
            }
            const thActions = document.getElementById('th-event-actions');
            if (thActions) {
                thActions.style.display = (role === 'teacher') ? 'none' : '';
            }

            const filtered = eventsData.filter(event => 
                event.title.toLowerCase().includes(search) || 
                (event.description || '').toLowerCase().includes(search) ||
                (event.location || '').toLowerCase().includes(search)
            );

            if (filtered.length === 0) {
                tbody.innerHTML = `<tr><td colspan="${role === 'teacher' ? 5 : 6}" style="text-align: center; color: #697386; padding: 30px;">No events found.</td></tr>`;
                return;
            }

            tbody.innerHTML = filtered.map(event => {
                const dateStr = new Date(event.event_date).toLocaleDateString();
                const timeStr = `${formatEventTime(event.start_time)} - ${formatEventTime(event.end_time)}`;

                const actionTd = role === 'teacher' ? '' : `
                    <td style="display:flex; gap:6px; align-items:center;">
                        <button class="btn-toggle-fee pay" onclick="openEditEventModal(${event.id})"
                            style="padding:4px 12px; font-size:11px; width:auto; margin-top:0; background:#0077be;">
                            Edit
                        </button>
                        <button class="btn-toggle-fee unpay" onclick="deleteEvent(${event.id})" 
                            style="padding:4px 12px; font-size:11px; width:auto; margin-top:0;">
                            Delete
                        </button>
                    </td>
                `;

                return `
                    <tr>
                        <td style="font-weight:600;">${event.title}</td>
                        <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${event.description}</td>
                        <td>${dateStr}</td>
                        <td>${timeStr}</td>
                        <td>${event.location}</td>
                        ${actionTd}
                    </tr>
                `;
            }).join('');
        }

        async function deleteEvent(id) {
            if (!confirm('Are you sure you want to delete this event?')) {
                return;
            }
            try {
                const res = await apiRequest(`/events/${id}`, {
                    method: 'DELETE'
                });
                showToast('Event deleted successfully', 'success');
                await fetchEvents();
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        function openCreateEventModal() {
            document.getElementById('event-form').reset();
            document.getElementById('event-edit-id').value = '';
            document.getElementById('event-modal-title').textContent = 'Create New School Event';
            document.getElementById('event-submit-btn').textContent = 'Create Event';
            document.getElementById('event-modal').classList.remove('hidden');
        }

        function openEditEventModal(id) {
            const ev = eventsData.find(e => e.id === id);
            if (!ev) return;

            document.getElementById('event-form').reset();
            document.getElementById('event-edit-id').value = id;
            document.getElementById('event-modal-title').textContent = 'Edit Event';
            document.getElementById('event-submit-btn').textContent = 'Save Changes';

            document.getElementById('event-title').value = ev.title || '';
            document.getElementById('event-description').value = ev.description || '';
            document.getElementById('event-date').value = ev.event_date ? ev.event_date.substring(0, 10) : '';
            document.getElementById('event-location').value = ev.location || '';
            document.getElementById('event-start-time').value = ev.start_time ? ev.start_time.substring(0, 5) : '';
            document.getElementById('event-end-time').value = ev.end_time ? ev.end_time.substring(0, 5) : '';
            document.getElementById('event-bring').value = ev.what_to_bring || '';
            document.getElementById('event-todo').value = ev.what_to_do || '';
            document.getElementById('event-goals').value = ev.learning_goals || '';

            document.getElementById('event-modal').classList.remove('hidden');
        }

        function closeEventModal() {
            document.getElementById('event-modal').classList.add('hidden');
        }

        async function handleCreateEvent(event) {
            event.preventDefault();

            const editId = document.getElementById('event-edit-id').value;
            const title = document.getElementById('event-title').value.trim();
            const description = document.getElementById('event-description').value.trim();
            const event_date = document.getElementById('event-date').value;
            let start_time = document.getElementById('event-start-time').value;
            let end_time = document.getElementById('event-end-time').value;
            const location = document.getElementById('event-location').value.trim();
            const what_to_bring = document.getElementById('event-bring').value.trim() || null;
            const what_to_do = document.getElementById('event-todo').value.trim() || null;
            const learning_goals = document.getElementById('event-goals').value.trim() || null;

            if (start_time && start_time.split(':').length === 2) start_time += ':00';
            if (end_time && end_time.split(':').length === 2) end_time += ':00';

            const payload = { title, description, event_date, start_time, end_time, location, what_to_bring, what_to_do, learning_goals };

            try {
                if (editId) {
                    await apiRequest(`/events/${editId}`, { method: 'PUT', body: JSON.stringify(payload) });
                    showToast('Event updated successfully', 'success');
                } else {
                    await apiRequest('/events', { method: 'POST', body: JSON.stringify(payload) });
                    showToast('Event created successfully', 'success');
                }
                closeEventModal();
                await fetchEvents();
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        // Complaints State & Handlers
        let complaintsData = [];

        async function fetchComplaints() {
            const tbody = document.getElementById('complaints-table-body');
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #697386; padding: 30px;">Loading complaints...</td></tr>';
            try {
                const data = await apiRequest('/complaints');
                if (Array.isArray(data)) {
                    complaintsData = data;
                    renderComplaintsTable();
                } else {
                    tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #ff4d4f; padding: 30px;">Failed to load complaints.</td></tr>';
                }
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: #ff4d4f; padding: 30px;">Error: ${err.message}</td></tr>`;
            }
        }

        function renderComplaintsTable() {
            const tbody = document.getElementById('complaints-table-body');
            const search = (document.getElementById('complaints-search')?.value || '').toLowerCase();
            const statusFilter = document.getElementById('complaints-status-filter')?.value || 'all';

            const filtered = complaintsData.filter(complaint => {
                const matchesSearch = 
                    complaint.category.toLowerCase().includes(search) || 
                    (complaint.message || '').toLowerCase().includes(search) ||
                    (complaint.user?.name || '').toLowerCase().includes(search);
                const matchesStatus = (statusFilter === 'all' || complaint.status === statusFilter);
                return matchesSearch && matchesStatus;
            });

            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #697386; padding: 30px;">No complaints found.</td></tr>';
                return;
            }

            tbody.innerHTML = filtered.map(complaint => {
                const dateStr = new Date(complaint.created_at).toLocaleDateString();
                const userName = complaint.user ? complaint.user.name : 'Unknown User';
                const userRole = complaint.user ? complaint.user.role.toUpperCase() : 'USER';
                
                let statusBadgeColor = '#ffc107'; // pending (yellow/orange)
                if (complaint.status === 'in_progress') statusBadgeColor = '#17a2b8'; // blue
                else if (complaint.status === 'resolved') statusBadgeColor = '#28a745'; // green
                else if (complaint.status === 'closed') statusBadgeColor = '#6c757d'; // grey

                return `
                    <tr>
                        <td>
                            <div style="font-weight: 600;">${userName}</div>
                            <div style="font-size: 10px; color: #697386;">${userRole}</div>
                        </td>
                        <td style="font-weight:600; color: #0077be;">${complaint.category}</td>
                        <td style="max-width: 250px; white-space: pre-wrap; font-size: 12px; line-height: 1.4;">${complaint.message}</td>
                        <td>
                            <span style="background: ${statusBadgeColor}15; color: ${statusBadgeColor}; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: bold; border: 1px solid ${statusBadgeColor}25;">
                                ${complaint.status.replace('_', ' ').toUpperCase()}
                            </span>
                        </td>
                        <td style="max-width: 200px; font-style: italic; font-size: 12px; color: #697386;">
                            ${complaint.admin_response || '<span style="color:#adb5bd;">No response yet</span>'}
                        </td>
                        <td>${dateStr}</td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <button class="btn-toggle-fee pay" onclick="openComplaintModal(${complaint.id})" 
                                    style="padding:4px 10px; font-size:11px; width:auto; margin-top:0; background: #4CAF50; border:none;">
                                    Reply
                                </button>
                                <button class="btn-toggle-fee unpay" onclick="deleteComplaint(${complaint.id})" 
                                    style="padding:4px 10px; font-size:11px; width:auto; margin-top:0;">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function openComplaintModal(id) {
            const complaint = complaintsData.find(c => c.id === id);
            if (!complaint) return;

            document.getElementById('complaint-id').value = complaint.id;
            document.getElementById('complaint-user-info').innerText = `${complaint.user ? complaint.user.name : 'Unknown'} (${complaint.user ? complaint.user.role.toUpperCase() : 'USER'})`;
            document.getElementById('complaint-category-info').innerText = complaint.category;
            document.getElementById('complaint-message-info').innerText = complaint.message;
            document.getElementById('complaint-status').value = complaint.status;
            document.getElementById('complaint-response').value = complaint.admin_response || '';

            document.getElementById('complaint-modal').classList.remove('hidden');
        }

        function closeComplaintModal() {
            document.getElementById('complaint-modal').classList.add('hidden');
        }

        async function handleUpdateComplaint(event) {
            event.preventDefault();

            const id = document.getElementById('complaint-id').value;
            const status = document.getElementById('complaint-status').value;
            const admin_response = document.getElementById('complaint-response').value.trim() || null;

            try {
                await apiRequest(`/complaints/${id}`, {
                    method: 'PUT',
                    body: JSON.stringify({ status, admin_response })
                });

                showToast('Complaint updated successfully', 'success');
                closeComplaintModal();
                await fetchComplaints();
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        async function deleteComplaint(id) {
            if (!confirm('Are you sure you want to delete this complaint?')) {
                return;
            }
            try {
                await apiRequest(`/complaints/${id}`, {
                    method: 'DELETE'
                });
                showToast('Complaint deleted successfully', 'success');
                await fetchComplaints();
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        async function submitTeacherComplaint(event) {
            event.preventDefault();
            
            const category = document.getElementById('teacher-complaint-category').value;
            const message = document.getElementById('teacher-complaint-message').value.trim();
            const submitBtn = event.target.querySelector('button[type="submit"]');
            
            if (!category || !message) {
                showToast('Please fill out all fields.', 'error');
                return;
            }
            
            const originalBtnText = submitBtn.innerText;
            submitBtn.innerText = 'Submitting...';
            submitBtn.disabled = true;
            
            try {
                const res = await apiRequest('/complaints', {
                    method: 'POST',
                    body: JSON.stringify({ category, message })
                });
                
                if (res && (res.complaint || res.message)) {
                    showToast('Complaint submitted successfully!', 'success');
                    document.getElementById('teacher-complaint-form').reset();
                    // Automatically reload history if they submit
                    const user = JSON.parse(localStorage.getItem('admin_user') || '{}');
                    if (user.id) {
                        fetchTeacherComplaintsHistory();
                    }
                } else {
                    showToast('Failed to submit complaint.', 'error');
                }
            } catch (err) {
                showToast(err.message || 'Error submitting complaint.', 'error');
            } finally {
                submitBtn.innerText = originalBtnText;
                submitBtn.disabled = false;
            }
        }

        function switchTeacherComplaintsTab(tab) {
            const newTabBtn = document.getElementById('teacher-tab-new');
            const historyTabBtn = document.getElementById('teacher-tab-history');
            const newPane = document.getElementById('teacher-complaint-new-pane');
            const historyPane = document.getElementById('teacher-complaint-history-pane');

            if (tab === 'new') {
                newTabBtn.style.background = '#0077be';
                newTabBtn.style.color = 'white';
                historyTabBtn.style.background = 'transparent';
                historyTabBtn.style.color = '#697386';
                newPane.classList.remove('hidden');
                historyPane.classList.add('hidden');
            } else {
                historyTabBtn.style.background = '#0077be';
                historyTabBtn.style.color = 'white';
                newTabBtn.style.background = 'transparent';
                newTabBtn.style.color = '#697386';
                newPane.classList.add('hidden');
                historyPane.classList.remove('hidden');
                fetchTeacherComplaintsHistory();
            }
        }

        async function fetchTeacherComplaintsHistory() {
            const container = document.getElementById('teacher-complaints-history-list');
            container.innerHTML = '<div style="text-align: center; color: #697386; padding: 40px;">Loading your history...</div>';
            
            try {
                const user = JSON.parse(localStorage.getItem('admin_user') || '{}');
                if (!user.id) {
                    container.innerHTML = '<div style="text-align: center; color: #ff4d4f; padding: 40px;">Error: User not found. Please re-login.</div>';
                    return;
                }
                
                const data = await apiRequest(`/complaints?user_id=${user.id}`);
                if (Array.isArray(data)) {
                    if (data.length === 0) {
                        container.innerHTML = `
                            <div class="glass-card" style="text-align: center; color: #697386; padding: 40px; border-radius: 16px;">
                                No complaints found in your history.
                            </div>`;
                        return;
                    }
                    
                    container.innerHTML = data.map(c => {
                        const dateStr = new Date(c.created_at).toLocaleDateString();
                        
                        let statusColor = '#ffc107'; // pending (yellow)
                        if (c.status === 'in_progress') statusColor = '#17a2b8'; // blue
                        else if (c.status === 'resolved') statusColor = '#28a745'; // green
                        else if (c.status === 'closed') statusColor = '#6c757d'; // grey

                        const responseHtml = c.admin_response 
                            ? `
                            <div style="margin-top: 14px; background: rgba(40, 167, 69, 0.04); border: 1px solid rgba(40, 167, 69, 0.15); padding: 14px; border-radius: 10px;">
                                <div style="display: flex; align-items: center; gap: 6px; font-size: 11px; font-weight: bold; color: #28a745; margin-bottom: 4px;">
                                    <svg viewBox="0 0 24 24" style="width:14px; height:14px; fill:#28a745;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                                    OFFICIAL RESPONSE:
                                </div>
                                <div style="font-size: 13px; color: #2e3a59; line-height: 1.4; font-style: normal; font-weight: 500;">${c.admin_response}</div>
                            </div>`
                            : '';

                        return `
                            <div class="glass-card" style="padding: 24px; border-radius: 16px; text-align: left; background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; gap: 10px;">
                                    <div>
                                        <h4 style="font-weight: 700; color: #1a1f36; font-size: 15px; margin-bottom: 4px; margin-top: 0;">${c.category}</h4>
                                        <span style="font-size: 11px; color: #697386;">${dateStr}</span>
                                    </div>
                                    <span style="background: ${statusColor}15; color: ${statusColor}; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; border: 1px solid ${statusColor}25; white-space: nowrap;">
                                        ${c.status.toUpperCase().replace('_', ' ')}
                                    </span>
                                </div>
                                <div style="font-size: 13px; color: #4f566b; line-height: 1.5; white-space: pre-wrap;">${c.message}</div>
                                ${responseHtml}
                            </div>
                        `;
                    }).join('');
                } else {
                    container.innerHTML = '<div style="text-align: center; color: #ff4d4f; padding: 40px;">Failed to load history.</div>';
                }
            } catch (err) {
                container.innerHTML = `<div style="text-align: center; color: #ff4d4f; padding: 40px;">Error: ${err.message}</div>`;
            }
        }

        // Leaves Management JS Functionality
        let leavesData = [];

        async function fetchLeavesAdmin() {
            try {
                const data = await apiRequest('/leaves');
                if (Array.isArray(data)) {
                    leavesData = data;
                    renderLeavesTable();
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        function renderLeavesTable() {
            const tbody = document.getElementById('leaves-table-body');
            tbody.innerHTML = '';

            const searchVal = document.getElementById('leaves-search').value.toLowerCase();
            const statusFilter = document.getElementById('leaves-status-filter').value;

            const filtered = leavesData.filter(l => {
                const teacherName = (l.user ? l.user.name : '').toLowerCase();
                const type = (l.leave_type || '').toLowerCase();
                const reason = (l.reason || '').toLowerCase();
                const matchesSearch = teacherName.includes(searchVal) || type.includes(searchVal) || reason.includes(searchVal);
                const matchesStatus = statusFilter === 'all' || l.status === statusFilter;
                return matchesSearch && matchesStatus;
            });

            if (filtered.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" style="text-align: center; color: #697386; padding: 30px;">No leave requests found.</td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = filtered.map(l => {
                const teacherName = l.user ? l.user.name : 'Unknown';
                const dateStr = new Date(l.created_at).toLocaleDateString();
                const startStr = new Date(l.start_date).toLocaleDateString();
                const endStr = new Date(l.end_date).toLocaleDateString();
                
                let statusColor = '#ffc107'; // pending
                if (l.status === 'approved') statusColor = '#28a745';
                else if (l.status === 'rejected') statusColor = '#dc3545';

                const actionButtons = l.status === 'pending'
                    ? `
                    <div style="display:flex; gap:6px;">
                        <button class="btn-toggle-fee pay" onclick="openLeaveModal(${l.id})" 
                            style="padding:4px 10px; font-size:11px; width:auto; margin-top:0; background: #0077be; border:none;">
                            Respond
                        </button>
                    </div>`
                    : `<span style="font-size:11px; color:#697386; font-style:italic;">No Actions</span>`;

                return `
                    <tr>
                        <td style="font-weight: 600; color: #1a1f36;">${teacherName}</td>
                        <td><span class="badge" style="background:rgba(0,0,0,0.05); color:#2e3a59; font-size:11px;">${l.leave_type}</span></td>
                        <td>${startStr}</td>
                        <td>${endStr}</td>
                        <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${l.reason}</td>
                        <td>
                            <span class="badge" style="background: ${statusColor}15; color: ${statusColor}; border: 1px solid ${statusColor}25; font-size: 11px; font-weight: 700;">
                                ${l.status.toUpperCase()}
                            </span>
                        </td>
                        <td style="max-width: 200px; font-style: italic; font-size: 12px; color: #697386;">
                            ${l.admin_response || '<span style="color:#adb5bd;">No response yet</span>'}
                        </td>
                        <td>${dateStr}</td>
                        <td>${actionButtons}</td>
                    </tr>
                `;
            }).join('');
        }

        function openLeaveModal(id) {
            const leave = leavesData.find(l => l.id === id);
            if (!leave) return;

            document.getElementById('leave-request-id').value = leave.id;
            document.getElementById('leave-teacher-info').innerText = `${leave.user ? leave.user.name : 'Unknown'}`;
            document.getElementById('leave-period-info').innerText = `${leave.leave_type} (${new Date(leave.start_date).toLocaleDateString()} to ${new Date(leave.end_date).toLocaleDateString()})`;
            document.getElementById('leave-reason-info').innerText = leave.reason;
            document.getElementById('leave-decision-status').value = 'approved';
            document.getElementById('leave-admin-response').value = '';

            document.getElementById('leave-modal').classList.remove('hidden');
        }

        function closeLeaveModal() {
            document.getElementById('leave-modal').classList.add('hidden');
        }

        async function handleUpdateLeave(event) {
            event.preventDefault();

            const id = document.getElementById('leave-request-id').value;
            const status = document.getElementById('leave-decision-status').value;
            const admin_response = document.getElementById('leave-admin-response').value.trim() || null;

            try {
                await apiRequest(`/leaves/${id}/respond`, {
                    method: 'PUT',
                    body: JSON.stringify({ status, admin_response })
                });

                showToast('Leave request updated successfully', 'success');
                closeLeaveModal();
                await fetchLeavesAdmin();
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        async function submitTeacherLeave(event) {
            event.preventDefault();
            
            const leave_type = document.getElementById('teacher-leave-type').value;
            const start_date = document.getElementById('teacher-leave-start').value;
            const end_date = document.getElementById('teacher-leave-end').value;
            const reason = document.getElementById('teacher-leave-reason').value.trim();
            const submitBtn = event.target.querySelector('button[type="submit"]');
            
            if (!leave_type || !start_date || !end_date || !reason) {
                showToast('Please fill out all fields.', 'error');
                return;
            }
            
            const originalBtnText = submitBtn.innerText;
            submitBtn.innerText = 'Submitting...';
            submitBtn.disabled = true;
            
            try {
                const res = await apiRequest('/leaves', {
                    method: 'POST',
                    body: JSON.stringify({ leave_type, start_date, end_date, reason })
                });
                
                if (res && (res.leave || res.message)) {
                    showToast('Leave request submitted successfully!', 'success');
                    document.getElementById('teacher-leave-form').reset();
                    fetchTeacherLeavesHistory();
                } else {
                    showToast('Failed to submit leave request.', 'error');
                }
            } catch (err) {
                showToast(err.message || 'Error submitting leave request.', 'error');
            } finally {
                submitBtn.innerText = originalBtnText;
                submitBtn.disabled = false;
            }
        }

        function switchTeacherLeavesTab(tab) {
            const newTabBtn = document.getElementById('teacher-leave-tab-new');
            const historyTabBtn = document.getElementById('teacher-leave-tab-history');
            const newPane = document.getElementById('teacher-leave-new-pane');
            const historyPane = document.getElementById('teacher-leave-history-pane');

            if (tab === 'new') {
                newTabBtn.style.background = '#0077be';
                newTabBtn.style.color = 'white';
                historyTabBtn.style.background = 'transparent';
                historyTabBtn.style.color = '#697386';
                newPane.classList.remove('hidden');
                historyPane.classList.add('hidden');
            } else {
                historyTabBtn.style.background = '#0077be';
                historyTabBtn.style.color = 'white';
                newTabBtn.style.background = 'transparent';
                newTabBtn.style.color = '#697386';
                newPane.classList.add('hidden');
                historyPane.classList.remove('hidden');
                fetchTeacherLeavesHistory();
            }
        }

        function switchUploadTab(tab) {
            const materialTab = document.getElementById('btn-upload-material-tab');
            const assignmentTab = document.getElementById('btn-upload-assignment-tab');
            const materialPane = document.getElementById('pane-upload-material');
            const assignmentPane = document.getElementById('pane-upload-assignment');

            if (tab === 'material') {
                materialTab.style.background = '#0077be';
                materialTab.style.color = 'white';
                materialTab.style.boxShadow = '0 4px 6px rgba(0,119,190,0.2)';
                assignmentTab.style.background = 'transparent';
                assignmentTab.style.color = '#697386';
                assignmentTab.style.boxShadow = 'none';
                materialPane.classList.remove('hidden');
                assignmentPane.classList.add('hidden');
            } else {
                assignmentTab.style.background = '#9c27b0';
                assignmentTab.style.color = 'white';
                assignmentTab.style.boxShadow = '0 4px 6px rgba(156,39,176,0.2)';
                materialTab.style.background = 'transparent';
                materialTab.style.color = '#697386';
                materialTab.style.boxShadow = 'none';
                materialPane.classList.add('hidden');
                assignmentPane.classList.remove('hidden');
            }
        }

        async function fetchTeacherLeavesHistory() {
            const container = document.getElementById('teacher-leaves-history-list');
            container.innerHTML = '<div style="text-align: center; color: #697386; padding: 40px;">Loading your history...</div>';
            
            try {
                const data = await apiRequest('/leaves/my');
                if (Array.isArray(data)) {
                    if (data.length === 0) {
                        container.innerHTML = `
                            <div class="glass-card" style="text-align: center; color: #697386; padding: 40px; border-radius: 16px;">
                                No leave requests found in your history.
                            </div>`;
                        return;
                    }
                    
                    container.innerHTML = data.map(l => {
                        const dateStr = new Date(l.created_at).toLocaleDateString();
                        const startStr = new Date(l.start_date).toLocaleDateString();
                        const endStr = new Date(l.end_date).toLocaleDateString();
                        
                        let statusColor = '#ffc107'; // pending
                        if (l.status === 'approved') statusColor = '#28a745';
                        else if (l.status === 'rejected') statusColor = '#dc3545';

                        const responseHtml = l.admin_response 
                            ? `
                            <div style="margin-top: 14px; background: rgba(40, 167, 69, 0.04); border: 1px solid rgba(40, 167, 69, 0.15); padding: 14px; border-radius: 10px;">
                                <div style="display: flex; align-items: center; gap: 6px; font-size: 11px; font-weight: bold; color: #28a745; margin-bottom: 4px;">
                                    <svg viewBox="0 0 24 24" style="width:14px; height:14px; fill:#28a745;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                                    OFFICIAL RESPONSE:
                                </div>
                                <div style="font-size: 13px; color: #2e3a59; line-height: 1.4; font-style: normal; font-weight: 500;">${l.admin_response}</div>
                            </div>`
                            : '';

                        return `
                            <div class="glass-card" style="padding: 24px; border-radius: 16px; text-align: left; background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; gap: 10px;">
                                    <div>
                                        <h4 style="font-weight: 700; color: #1a1f36; font-size: 15px; margin-bottom: 4px; margin-top: 0;">${l.leave_type}</h4>
                                        <span style="font-size: 11px; color: #697386;">Period: ${startStr} to ${endStr} (Submitted on ${dateStr})</span>
                                    </div>
                                    <span style="background: ${statusColor}15; color: ${statusColor}; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; border: 1px solid ${statusColor}25; white-space: nowrap;">
                                        ${l.status.toUpperCase()}
                                    </span>
                                </div>
                                <div style="font-size: 13px; color: #4f566b; line-height: 1.5; white-space: pre-wrap;">${l.reason}</div>
                                ${responseHtml}
                            </div>
                        `;
                    }).join('');
                } else {
                    container.innerHTML = '<div style="text-align: center; color: #ff4d4f; padding: 40px;">Failed to load history.</div>';
                }
            } catch (err) {
                container.innerHTML = `<div style="text-align: center; color: #ff4d4f; padding: 40px;">Error: ${err.message}</div>`;
            }
        }

        // Teacher-Specific JS Functionality
        let teacherTimetableDay = 'Monday';
        let simpleTimetableRecords = [];

        async function fetchTeacherTimetable() {
            try {
                const res = await apiRequest('/simple-timetables');
                if (res.success && res.data) {
                    simpleTimetableRecords = res.data;
                    renderTeacherTimetable();
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        function selectTeacherTimetableDay(day) {
            teacherTimetableDay = day;
            document.querySelectorAll('.day-selector-pills .pill').forEach(pill => {
                if (pill.innerText.trim().toLowerCase() === day.substring(0,3).toLowerCase()) {
                    pill.classList.add('active');
                } else {
                    pill.classList.remove('active');
                }
            });
            document.getElementById('teacher-timetable-day-name').innerText = day;
            renderTeacherTimetable();
        }

        function renderTeacherTimetable() {
            const user = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const teacherId = user.teacher ? user.teacher.id : null;
            const container = document.getElementById('teacher-timetable-timeline');
            container.innerHTML = '';

            if (!teacherId) {
                container.innerHTML = '<div style="text-align: center; color: #697386; padding: 30px;">Teacher details not found.</div>';
                return;
            }

            const userId = user.id;
            const dayRecord = simpleTimetableRecords.find(t => 
                t.day.toLowerCase() === teacherTimetableDay.toLowerCase() && 
                (userId && t.grade.toLowerCase().includes(`teacher:${userId}`))
            );

            if (!dayRecord) {
                container.innerHTML = `<div style="text-align: center; color: #697386; padding: 30px;">No teaching schedule assigned for ${teacherTimetableDay}.</div>`;
                return;
            }

            let hasSlots = false;
            for (const [key, label] of Object.entries(slotsMetadata)) {
                const value = dayRecord[key];
                if (value && value.trim()) {
                    hasSlots = true;
                    let subjectText = value;
                    let gradeText = 'General Class';
                    if (value.includes('(') && value.includes(')')) {
                        const startIndex = value.indexOf('(');
                        const endIndex = value.indexOf(')');
                        if (endIndex > startIndex) {
                            gradeText = value.substring(startIndex + 1, endIndex);
                            subjectText = value.substring(0, startIndex).trim();
                        }
                    }

                    const item = document.createElement('div');
                    item.className = 'timeline-item';
                    item.innerHTML = `
                        <div>
                            <div style="font-size: 15px; font-weight: 700; color: #1a1f36;">${subjectText}</div>
                            <div style="font-size: 12px; color: #0077be; font-weight: 600; margin-top: 4px;">${gradeText}</div>
                        </div>
                        <div style="font-size: 13px; font-weight: 600; color: #697386; background: rgba(0,0,0,0.05); padding: 6px 12px; border-radius: 12px;">
                            ${label}
                        </div>
                    `;
                    container.appendChild(item);
                }
            }

            if (!hasSlots) {
                container.innerHTML = `<div style="text-align: center; color: #697386; padding: 30px;">No active classes scheduled for ${teacherTimetableDay}.</div>`;
            }
        }

        // ─── MEAL PLAN ────────────────────────────────────────────────────
        let mealPlanRecords = [];
        let mealPlanSelectedDay = 'Monday';

        async function fetchMealPlans() {
            try {
                const res = await apiRequest('/meal-plans');
                if (res.success && res.data) {
                    mealPlanRecords = res.data;
                    renderMealPlanDay();
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        function selectMealPlanDay(day) {
            mealPlanSelectedDay = day;
            document.querySelectorAll('#meal-plan-day-pills .pill').forEach(pill => {
                pill.classList.toggle('active', pill.innerText.trim().toLowerCase() === day.substring(0, 3).toLowerCase());
            });
            document.getElementById('meal-plan-day-name').innerText = day;
            renderMealPlanDay();
        }

        function renderMealPlanDay() {
            const record = mealPlanRecords.find(m => m.day.toLowerCase() === mealPlanSelectedDay.toLowerCase());
            document.getElementById('meal-plan-meal').value = record ? (record.meal || '') : '';
            document.getElementById('meal-plan-notes').value = record ? (record.notes || '') : '';

            const lastUpdatedEl = document.getElementById('meal-plan-last-updated');
            if (record && record.updated_at) {
                const editor = record.updated_by ? ` by ${record.updated_by.name}` : '';
                lastUpdatedEl.innerText = `Last updated ${new Date(record.updated_at).toLocaleString()}${editor}`;
            } else {
                lastUpdatedEl.innerText = 'No meal plan set for this day yet.';
            }
        }

        async function saveMealPlan(e) {
            e.preventDefault();
            const saveBtn = document.getElementById('meal-plan-save-btn');
            saveBtn.disabled = true;
            const originalText = saveBtn.innerText;
            saveBtn.innerText = 'Saving...';

            try {
                const res = await apiRequest('/meal-plans', {
                    method: 'POST',
                    body: JSON.stringify({
                        day: mealPlanSelectedDay,
                        meal: document.getElementById('meal-plan-meal').value.trim(),
                        notes: document.getElementById('meal-plan-notes').value.trim(),
                    })
                });

                if (res.success) {
                    showToast('Meal plan saved successfully!', 'success');
                    await fetchMealPlans();
                } else {
                    showToast(res.message || 'Failed to save meal plan.', 'error');
                }
            } catch (err) {
                showToast(err.message, 'error');
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerText = originalText;
            }
        }

        async function handleUploadMaterial(e) {
            e.preventDefault();
            const grade = document.getElementById('material-grade').value;
            const subject = document.getElementById('material-subject-select').value;
            const topic = (document.getElementById('material-topic-desc')?.value || '').trim();
            const pdfFile = document.getElementById('material-pdf').files[0];

            if (!pdfFile) {
                showToast('Please choose a material PDF file', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('grade', grade);
            formData.append('subject_name', subject);
            if (topic) formData.append('topic', topic);
            formData.append('pdf', pdfFile);

            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerText;
            submitBtn.innerText = 'Uploading...';
            submitBtn.disabled = true;

            try {
                const token = localStorage.getItem('admin_token');
                const res = await fetch(`${API_BASE}/subjects`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    showToast('Material uploaded successfully!', 'success');
                    e.target.reset();
                    setupTeacherDashboard(JSON.parse(localStorage.getItem('admin_user') || '{}'));
                } else {
                    showToast(data.message || 'Failed to upload material', 'error');
                }
            } catch (err) {
                showToast(err.message, 'error');
            } finally {
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
            }
        }

        async function handleUploadAssignment(e) {
            e.preventDefault();
            const grade = document.getElementById('assignment-grade').value;
            const subject = document.getElementById('assignment-subject-select').value;
            const title = (document.getElementById('assignment-title')?.value || '').trim();
            const dueTime = document.getElementById('assignment-due-time').value.trim();
            const assignmentFile = document.getElementById('assignment-pdf').files[0];

            if (!assignmentFile) {
                showToast('Please choose an assignment PDF file', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('grade', grade);
            formData.append('subject_name', subject);
            if (title) formData.append('topic', title);
            formData.append('due_time', dueTime);
            formData.append('assignment', assignmentFile);

            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerText;
            submitBtn.innerText = 'Uploading...';
            submitBtn.disabled = true;

            try {
                const token = localStorage.getItem('admin_token');
                const res = await fetch(`${API_BASE}/subjects`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    showToast('Assignment uploaded successfully!', 'success');
                    e.target.reset();
                    setupTeacherDashboard(JSON.parse(localStorage.getItem('admin_user') || '{}'));
                } else {
                    showToast(data.message || 'Failed to upload assignment', 'error');
                }
            } catch (err) {
                showToast(err.message, 'error');
            } finally {
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
            }
        }

        let studentSubmissions = [];

        function isSubmissionAccessibleToTeacher(sub) {
            if (!sub || !sub.subject) return false;
            
            const subGrade = String(sub.subject.grade || '').trim();
            const subName = String(sub.subject.subject_name || sub.subject.name || '').trim().toLowerCase();
            const subTeacherId = sub.subject.teacher_id ? String(sub.subject.teacher_id) : null;
            const subId = sub.subject.id ? String(sub.subject.id) : null;

            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const teacherId = adminObj.teacher ? String(adminObj.teacher.id) : null;
            const userId = adminObj.id ? String(adminObj.id) : null;

            // If the subject is explicitly assigned to another teacher, do not show to this teacher
            if (subTeacherId && ((teacherId && subTeacherId !== teacherId) && (!userId || subTeacherId !== userId))) {
                return false;
            }

            // 1. Direct teacher_id match on subject
            if ((teacherId && subTeacherId === teacherId) || (userId && subTeacherId === userId)) return true;

            // 2. Subject in teacher's grade subjects map (e.g. from timetable or subjects table)
            const gradeMap = window.currentTeacherGradeSubjectsMap || {};
            for (const [gName, subSet] of Object.entries(gradeMap)) {
                if (isExactGradeMatch(gName, subGrade)) {
                    if (subSet && (subSet instanceof Set || Array.isArray(subSet))) {
                        const subArray = Array.from(subSet).map(s => String(s).trim().toLowerCase());
                        if (subArray.some(s => s === subName || subName.includes(s) || s.includes(subName))) return true;
                    }
                }
            }

            // 3. allSubjectsData check
            if (Array.isArray(window.allSubjectsData) && window.allSubjectsData.length > 0) {
                const isAssigned = window.allSubjectsData.some(s => {
                    const matchesTeacher = (teacherId && String(s.teacher_id) === teacherId) || (userId && String(s.teacher_id) === userId);
                    const matchesGrade = isExactGradeMatch(s.grade, subGrade);
                    const sName = (s.subject_name || s.name || '').trim().toLowerCase();
                    return matchesTeacher && matchesGrade && ((sName === subName) || (String(s.id) === subId));
                });
                if (isAssigned) return true;
            }

            // 4. Specialization check
            if (adminObj.teacher && adminObj.teacher.subject_specialization) {
                const spec = adminObj.teacher.subject_specialization.toLowerCase();
                const teachesGrade = (window.currentTeacherGrades || []).some(g => isExactGradeMatch(g, subGrade));
                if (teachesGrade && spec.split(',').some(s => s.trim() && subName.includes(s.trim()))) return true;
            }

            return false;
        }

        async function fetchStudentSubmissions() {
            try {
                const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
                const role = ((adminObj && adminObj.role) || localStorage.getItem('user_role') || 'admin').toLowerCase();
                const teacherId = adminObj.teacher ? adminObj.teacher.id : '';
                const userId = adminObj.id || '';

                if (role === 'teacher') {
                    if (!window.currentTeacherGradeSubjectsMap || !window.currentTeacherClassGrades) {
                        await setupTeacherDashboard(adminObj);
                    }
                    if (!window.allSubjectsData) {
                        try {
                            const sRes = await apiRequest('/subjects');
                            if (sRes.success && sRes.data) window.allSubjectsData = sRes.data;
                        } catch (e) {}
                    }
                }

                const url = (role === 'teacher')
                    ? `/subject-submissions?teacher_id=${teacherId}&user_id=${userId}`
                    : `/subject-submissions`;

                const res = await apiRequest(url);
                if (res.success && res.data) {
                    studentSubmissions = res.data;
                    
                    // Populate Grade Filter Dropdown with assigned grades for teacher
                    const gradeSelect = document.getElementById('teacher-submissions-grade-filter');
                    if (gradeSelect) {
                        gradeSelect.innerHTML = '<option value="">All Assigned Grades</option>';
                        
                        let gradesToPopulate = [];
                        if (role === 'teacher' && window.currentTeacherGrades && window.currentTeacherGrades.length > 0) {
                            gradesToPopulate = window.currentTeacherGrades;
                        } else {
                            const uniqueGrades = new Set();
                            studentSubmissions.forEach(sub => {
                                if (sub.subject && sub.subject.grade) {
                                    uniqueGrades.add(sub.subject.grade.trim());
                                }
                            });
                            gradesToPopulate = Array.from(uniqueGrades).sort();
                        }

                        gradesToPopulate.forEach(grade => {
                            gradeSelect.innerHTML += `<option value="${grade}">${grade}</option>`;
                        });
                        
                        gradeSelect.onchange = renderStudentSubmissions;
                    }
                    
                    const searchEl = document.getElementById('teacher-submissions-search');
                    if (searchEl) searchEl.oninput = renderStudentSubmissions;
                    
                    renderStudentSubmissions();
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        function renderStudentSubmissions() {
            const tbody = document.getElementById('teacher-submissions-table-body');
            tbody.innerHTML = '';
            
            const searchVal = (document.getElementById('teacher-submissions-search')?.value || '').toLowerCase().trim();
            const gradeFilter = (document.getElementById('teacher-submissions-grade-filter')?.value || '').trim();
            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const role = ((adminObj && adminObj.role) || localStorage.getItem('user_role') || 'admin').toLowerCase();
            
            const filtered = studentSubmissions.filter(sub => {
                if (role === 'teacher' && !isSubmissionAccessibleToTeacher(sub)) {
                    return false;
                }

                const studentName = (sub.user ? sub.user.name : '').toLowerCase();
                const subjectName = (sub.subject ? (sub.subject.subject_name || '') : '').toLowerCase();
                const studentGrade = (sub.subject ? (sub.subject.grade || '') : '').trim();
                
                const matchesSearch = !searchVal || studentName.includes(searchVal) || subjectName.includes(searchVal);
                const matchesGrade = gradeFilter ? isExactGradeMatch(gradeFilter, studentGrade) : true;
                
                return matchesSearch && matchesGrade;
            });

            if (filtered.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" style="text-align: center; color: #697386; padding: 30px;">No student submissions found.</td>
                    </tr>
                `;
                return;
            }

            filtered.forEach(sub => {
                const studentName = sub.user ? sub.user.name : 'Unknown Student';
                const gradeClass = sub.subject ? (sub.subject.grade || '—') : '—';
                const subjectName = sub.subject ? sub.subject.subject_name : 'General Subject';
                const fileLink = sub.assignment_pdf ? `<a href="${getFileUrl(sub.assignment_pdf)}" target="_blank" style="color:#0077be; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:5px;">
                    <svg viewBox="0 0 24 24" style="width:16px; height:16px; fill:#0077be;"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                    View PDF
                </a>` : 'No file';
                
                const gradeBadge = sub.grade ? `<span class="badge status-present" style="background:#4CAF50; color:white; font-size:12px;">${sub.grade}</span>` : `<span class="badge status-absent" style="background:#ff4d4f; color:white; font-size:12px;">Ungraded</span>`;
                const feedbackText = sub.feedback ? sub.feedback : '—';
                const submittedDate = sub.created_at ? new Date(sub.created_at).toLocaleDateString() : '—';

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="font-weight:600; color:#1a1f36;">${studentName}</td>
                    <td><span style="font-weight: 600; color: #4CAF50;">${gradeClass}</span></td>
                    <td>${subjectName}</td>
                    <td>${fileLink}</td>
                    <td>${submittedDate}</td>
                    <td>${gradeBadge}</td>
                    <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">${feedbackText}</td>
                    <td>
                        <button class="btn-toggle-fee pay" onclick="openGradeSubmissionModal(${sub.id}, '${studentName.replace(/'/g, "\\'")}', '${subjectName.replace(/'/g, "\\'")}', '${(sub.grade || '').replace(/'/g, "\\'")}', '${(sub.feedback || '').replace(/'/g, "\\'")}')" style="padding:6px 12px; font-size:12px; width:auto; border:none; background:#0077be; color:white; cursor:pointer;">
                            Grade
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Search inputs
        document.addEventListener('DOMContentLoaded', () => {
            const subSearch = document.getElementById('teacher-submissions-search');
            if (subSearch) {
                subSearch.addEventListener('input', renderStudentSubmissions);
            }
            
            // Exam Results filters
            const examSearch = document.getElementById('exam-results-search');
            if (examSearch) {
                examSearch.addEventListener('input', renderExamResults);
            }
            const examFilterClass = document.getElementById('exam-results-filter-class');
            if (examFilterClass) {
                examFilterClass.addEventListener('change', () => {
                    updateExamResultSubjectsForSelectedClass();
                    renderExamResults();
                });
            }
            const examFilterSubject = document.getElementById('exam-results-filter-subject');
            if (examFilterSubject) {
                examFilterSubject.addEventListener('change', renderExamResults);
            }
            const examFilterTerm = document.getElementById('exam-results-filter-term');
            if (examFilterTerm) {
                examFilterTerm.addEventListener('change', renderExamResults);
            }
            const examFilterYear = document.getElementById('exam-results-filter-year');
            if (examFilterYear) {
                examFilterYear.addEventListener('change', renderExamResults);
            }
        });

        function openGradeSubmissionModal(id, studentName, subjectName, currentGrade, currentFeedback) {
            document.getElementById('grade-submission-id').value = id;
            document.getElementById('grade-student-name').innerText = studentName;
            document.getElementById('grade-subject-name').innerText = subjectName;
            document.getElementById('grade-score').value = currentGrade;
            document.getElementById('grade-feedback').value = currentFeedback;
            
            document.getElementById('grade-submission-modal').classList.remove('hidden');
        }

        function closeGradeSubmissionModal() {
            document.getElementById('grade-submission-modal').classList.add('hidden');
        }

        function openStudentSubmitModal(subjectId, subjectName, grade) {
            document.getElementById('student-submit-subject-id').value = subjectId;
            document.getElementById('student-submit-subject-name').innerText = `${subjectName} (${grade})`;
            document.getElementById('student-submit-modal').classList.remove('hidden');
        }

        function closeStudentSubmitModal() {
            document.getElementById('student-submit-modal').classList.add('hidden');
        }

        async function handleStudentSubmitAssignment(e) {
            e.preventDefault();
            const subjectId = document.getElementById('student-submit-subject-id').value;
            const pdfFile = document.getElementById('student-assignment-pdf').files[0];
            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const userId = adminObj.id;

            if (!pdfFile) {
                showToast('Please select a PDF file to submit', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('user_id', userId);
            formData.append('subject_id', subjectId);
            formData.append('assignment_pdf', pdfFile);

            const submitBtn = e.target.querySelector('button[type="submit"]');
            const origText = submitBtn.innerText;
            submitBtn.innerText = 'Submitting...';
            submitBtn.disabled = true;

            try {
                const token = localStorage.getItem('admin_token');
                const res = await fetch(`${API_BASE}/subject-submissions`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    showToast('Assignment submitted successfully!', 'success');
                    closeStudentSubmitModal();
                    fetchTeacherUploadedMaterials();
                } else {
                    showToast(data.message || 'Failed to submit assignment', 'error');
                }
            } catch (err) {
                showToast(err.message, 'error');
            } finally {
                submitBtn.innerText = origText;
                submitBtn.disabled = false;
            }
        }

        async function submitGradeSubmission(e) {
            e.preventDefault();
            const id = document.getElementById('grade-submission-id').value;
            const grade = document.getElementById('grade-score').value.trim();
            const feedback = document.getElementById('grade-feedback').value.trim();

            try {
                const res = await apiRequest(`/subject-submissions/${id}/grade`, {
                    method: 'PUT',
                    body: JSON.stringify({ grade, feedback })
                });

                if (res.success) {
                    showToast('Submission graded successfully!', 'success');
                    closeGradeSubmissionModal();
                    fetchStudentSubmissions();
                    setupTeacherDashboard(JSON.parse(localStorage.getItem('admin_user') || '{}'));
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        let examResultsData = [];
        let examStudentsList = [];
        let examSubjectsLoaded = false;
        let examStudentsLoaded = false;

        function isExactGradeMatch(teacherGrade, studentGrade) {
            if (!teacherGrade || !studentGrade) return false;
            const tNum = String(teacherGrade).replace(/\D+/g, '');
            const sNum = String(studentGrade).replace(/\D+/g, '');
            if (tNum && sNum) {
                return tNum === sNum;
            }
            const tClean = String(teacherGrade).trim().toLowerCase().replace(/^grade\s*/, '');
            const sClean = String(studentGrade).trim().toLowerCase().replace(/^grade\s*/, '');
            return tClean === sClean;
        }

        function isClassTeacherOfGrade(g, adminObj) {
            if (!g || !adminObj) return false;
            const userId = adminObj.id ? String(adminObj.id) : null;
            const teacherId = adminObj.teacher ? String(adminObj.teacher.id) : null;

            const ctId = g.class_teacher_id ? String(g.class_teacher_id) : null;
            const ctTeacherId = (g.classTeacher && g.classTeacher.id) ? String(g.classTeacher.id) : null;
            const ctUserId = (g.classTeacher && g.classTeacher.user_id) ? String(g.classTeacher.user_id) : ((g.classTeacher && g.classTeacher.user) ? String(g.classTeacher.user.id) : null);

            if (teacherId && ctId && ctId === teacherId) return true;
            if (userId && ctId && ctId === userId) return true;
            if (teacherId && ctTeacherId && ctTeacherId === teacherId) return true;
            if (userId && ctUserId && ctUserId === userId) return true;

            return false;
        }



        async function fetchExamResults() {
            const tbody = document.getElementById('exam-results-table-body');
            tbody.innerHTML = '<tr><td colspan="9" style="text-align: center; color: #697386; padding: 30px;">Loading exam results...</td></tr>';
            
            try {
                const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
                const role = ((adminObj && adminObj.role) || localStorage.getItem('user_role') || 'teacher').toLowerCase();

                if (role === 'teacher') {
                    await setupTeacherDashboard(adminObj);
                }

                // Fetch exam results
                const res = await apiRequest('/exam-results?all=true');
                examResultsData = res; // ExamResultController returns array directly
                
                // Fetch subjects if not loaded
                if (!examSubjectsLoaded || window.lastExamResultsRole !== role) {
                    const subjectsRes = await apiRequest('/subjects');
                    if (subjectsRes.success && subjectsRes.data) {
                        window.allSubjectsData = subjectsRes.data.filter(s => s && s.subject_name && s.grade);
                        const subjectModalSelect = document.getElementById('exam-result-subject');
                        if (subjectModalSelect) {
                            subjectModalSelect.innerHTML = '<option value="" disabled selected>Select Subject</option>';
                            const modalUnique = new Map();
                            window.allSubjectsData.forEach(sub => {
                                const name = sub.subject_name;
                                if (name && !modalUnique.has(name.toLowerCase())) {
                                    modalUnique.set(name.toLowerCase(), { id: sub.id, name: name });
                                }
                            });
                            Array.from(modalUnique.values()).sort((a, b) => a.name.localeCompare(b.name)).forEach(sub => {
                                subjectModalSelect.innerHTML += `<option value="${sub.id}">${sub.name}</option>`;
                            });
                        }
                        updateExamResultSubjectsForSelectedClass();
                        window.lastExamResultsRole = role;
                        examSubjectsLoaded = true;
                    }
                } else {
                    updateExamResultSubjectsForSelectedClass();
                }
                
                // Fetch grades if not loaded
                if (gradesData.length === 0) {
                    try {
                        const gradesRes = await apiRequest('/admin/grades');
                        if (gradesRes.success && gradesRes.data) {
                            gradesData = gradesRes.data;
                        }
                    } catch(e) {}
                }
                
                // Fetch students if not loaded
                if (!examStudentsLoaded) {
                    const usersRes = await apiRequest('/admin/users?all=true');
                    if (usersRes.success && usersRes.data) {
                        examStudentsList = usersRes.data.filter(user => user.role.toLowerCase() === 'student' && user.student);
                        examStudentsLoaded = true;
                    }
                }
                
                // Re-populate Student Dropdown
                await populateExamResultStudentsDropdown();
                
                // Populate Grade Dropdown
                populateExamResultGradesDropdown();

                renderExamResults();
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="9" style="text-align: center; color: #ff4d4f; padding: 30px;">Failed to load exam results: ${err.message}</td></tr>`;
            }
        }
        
        function isExamResultAccessibleToTeacher(res) {
            if (!res || !res.student) return false;
            
            const rawStudentGrade = String(res.student.grade || '').trim();
            const subjectName = (res.subject ? (res.subject.subject_name || res.subject.name || '') : '').trim().toLowerCase();
            const subjectId = String(res.subject_id || (res.subject ? res.subject.id : ''));

            // 1. Is this student in the teacher's ASSIGNED CLASS (Class Teacher)?
            const classTeacherGrades = window.currentTeacherClassGrades || [];
            const isAssignedClass = classTeacherGrades.some(g => isExactGradeMatch(g, rawStudentGrade));
            if (isAssignedClass) {
                // Teacher is the Class Teacher -> Has full access to ALL results of this assigned class!
                return true;
            }

            // 2. In other grades: ONLY results of the subjects assigned to them in that grade
            const gradeMap = window.currentTeacherGradeSubjectsMap || {};
            for (const [gName, subSet] of Object.entries(gradeMap)) {
                if (isExactGradeMatch(gName, rawStudentGrade)) {
                    if (subSet && (subSet instanceof Set || Array.isArray(subSet))) {
                        const subArray = Array.from(subSet).map(s => String(s).trim().toLowerCase());
                        if (subArray.some(s => s === subjectName || subjectName.includes(s) || s.includes(subjectName))) {
                            return true;
                        }
                    }
                }
            }

            // Fallback: check subjectsData where teacher_id matches for this grade & subject
            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const teacherId = adminObj.teacher ? String(adminObj.teacher.id) : null;
            const userId = adminObj.id ? String(adminObj.id) : null;

            if (Array.isArray(window.allSubjectsData) && window.allSubjectsData.length > 0) {
                const isSubjectAssigned = window.allSubjectsData.some(s => {
                    const matchesTeacher = (teacherId && String(s.teacher_id) === teacherId) || (userId && String(s.teacher_id) === userId);
                    const matchesGrade = isExactGradeMatch(s.grade, rawStudentGrade);
                    const sName = (s.subject_name || s.name || '').trim().toLowerCase();
                    const matchesSub = (sName === subjectName) || (String(s.id) === subjectId);
                    return matchesTeacher && matchesGrade && matchesSub;
                });
                if (isSubjectAssigned) return true;
            }

            // Fallback check teacher specialization if grade is in currentTeacherGrades
            if (adminObj.teacher && adminObj.teacher.subject_specialization) {
                const spec = adminObj.teacher.subject_specialization.toLowerCase();
                const teachesGrade = (window.currentTeacherGrades || []).some(g => isExactGradeMatch(g, rawStudentGrade));
                if (teachesGrade && spec.split(',').some(s => s.trim() && subjectName.includes(s.trim()))) {
                    return true;
                }
            }

            return false;
        }

        function updateExamResultSubjectsForSelectedClass() {
            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const role = ((adminObj && adminObj.role) || localStorage.getItem('user_role') || 'teacher').toLowerCase();

            const filterClassSelect = document.getElementById('exam-results-filter-class');
            const subjectFilter = document.getElementById('exam-results-filter-subject');
            if (!subjectFilter) return;

            const selectedGrade = filterClassSelect ? (filterClassSelect.value || '').trim() : '';
            const isClassTeacher = (window.currentTeacherClassGrades || []).some(g => isExactGradeMatch(g, selectedGrade));

            const uniqueSubjects = new Map();

            if (role === 'admin') {
                // Admin: sees only real subjects added in the system (or filtered by selected class)
                if (Array.isArray(window.allSubjectsData)) {
                    window.allSubjectsData.forEach(sub => {
                        const name = sub.subject_name;
                        const grade = (sub.grade || '').trim();
                        if (name && grade) {
                            if (!selectedGrade || isExactGradeMatch(grade, selectedGrade)) {
                                if (!uniqueSubjects.has(name.toLowerCase())) {
                                    uniqueSubjects.set(name.toLowerCase(), { id: sub.id, name: name });
                                }
                            }
                        }
                    });
                }
                if (Array.isArray(examResultsData)) {
                    examResultsData.forEach(r => {
                        if (r.subject && r.subject.subject_name) {
                            const name = r.subject.subject_name;
                            const studentGrade = (r.student && r.student.grade) ? r.student.grade : (r.subject.grade || '');
                            if (!selectedGrade || isExactGradeMatch(studentGrade, selectedGrade)) {
                                if (!uniqueSubjects.has(name.toLowerCase())) {
                                    uniqueSubjects.set(name.toLowerCase(), { id: r.subject_id || r.subject.id, name: name });
                                }
                            }
                        }
                    });
                }
            } else if (isClassTeacher) {
                // Class Teacher: has access to ALL subjects in their class!
                if (Array.isArray(window.allSubjectsData)) {
                    window.allSubjectsData.forEach(sub => {
                        const name = sub.subject_name;
                        if (name && sub.grade && isExactGradeMatch(sub.grade, selectedGrade)) {
                            if (!uniqueSubjects.has(name.toLowerCase())) {
                                uniqueSubjects.set(name.toLowerCase(), { id: sub.id, name: name });
                            }
                        }
                    });
                }
                if (Array.isArray(examResultsData)) {
                    examResultsData.forEach(r => {
                        if (r.student && isExactGradeMatch(r.student.grade, selectedGrade) && r.subject && r.subject.subject_name) {
                            const name = r.subject.subject_name;
                            if (!uniqueSubjects.has(name.toLowerCase())) {
                                uniqueSubjects.set(name.toLowerCase(), { id: r.subject_id || r.subject.id, name: name });
                            }
                        }
                    });
                }
            } else {
                // Not Class Teacher: ONLY subjects assigned to this teacher in this grade!
                const gradeMap = window.currentTeacherGradeSubjectsMap || {};
                let matchedGradeKey = Object.keys(gradeMap).find(k => isExactGradeMatch(k, selectedGrade));
                if (matchedGradeKey && gradeMap[matchedGradeKey]) {
                    Array.from(gradeMap[matchedGradeKey]).forEach(subName => {
                        if (subName && !uniqueSubjects.has(subName.toLowerCase())) {
                            const subObj = Array.isArray(window.allSubjectsData) 
                                ? window.allSubjectsData.find(s => s.subject_name && s.subject_name.toLowerCase() === subName.toLowerCase())
                                : null;
                            uniqueSubjects.set(subName.toLowerCase(), { id: subObj ? subObj.id : subName, name: subName });
                        }
                    });
                }
                // Fallback: check allSubjectsData where teacher_id matches for this grade
                const teacherId = adminObj.teacher ? String(adminObj.teacher.id) : null;
                const userId = adminObj.id ? String(adminObj.id) : null;
                if (Array.isArray(window.allSubjectsData)) {
                    window.allSubjectsData.forEach(s => {
                        const matchesTeacher = (teacherId && String(s.teacher_id) === teacherId) || (userId && String(s.teacher_id) === userId);
                        if (matchesTeacher && isExactGradeMatch(s.grade, selectedGrade) && s.subject_name) {
                            if (!uniqueSubjects.has(s.subject_name.toLowerCase())) {
                                uniqueSubjects.set(s.subject_name.toLowerCase(), { id: s.id, name: s.subject_name });
                            }
                        }
                    });
                }
                // Fallback from specialization
                if (uniqueSubjects.size === 0 && adminObj.teacher && adminObj.teacher.subject_specialization) {
                    const specs = adminObj.teacher.subject_specialization.split(',');
                    specs.forEach(spec => {
                        const sTrim = spec.trim();
                        if (sTrim && !uniqueSubjects.has(sTrim.toLowerCase())) {
                            const subObj = Array.isArray(window.allSubjectsData)
                                ? window.allSubjectsData.find(s => s.subject_name && s.subject_name.toLowerCase() === sTrim.toLowerCase())
                                : null;
                            uniqueSubjects.set(sTrim.toLowerCase(), { id: subObj ? subObj.id : sTrim, name: sTrim });
                        }
                    });
                }
            }

            const currentVal = subjectFilter.value;
            subjectFilter.innerHTML = '';

            if (role === 'admin' || isClassTeacher) {
                subjectFilter.innerHTML = '<option value="">All Subjects</option>';
            }

            if (uniqueSubjects.size === 0) {
                if (role !== 'admin' && !isClassTeacher) {
                    subjectFilter.innerHTML = '<option value="">No assigned subjects in this class</option>';
                }
            } else {
                const sorted = Array.from(uniqueSubjects.values()).sort((a, b) => a.name.localeCompare(b.name));
                sorted.forEach(sub => {
                    subjectFilter.innerHTML += `<option value="${sub.name}">${sub.name}</option>`;
                });
            }

            if (currentVal && Array.from(subjectFilter.options).some(o => String(o.value).toLowerCase() === String(currentVal).toLowerCase())) {
                subjectFilter.value = currentVal;
            } else if (role !== 'admin' && !isClassTeacher && subjectFilter.options.length > 0) {
                subjectFilter.selectedIndex = 0;
            } else {
                subjectFilter.value = '';
            }
        }
        
        function populateExamResultGradesDropdown() {
            const gradeSelect = document.getElementById('exam-result-grade');
            const filterClassSelect = document.getElementById('exam-results-filter-class');
            if (!gradeSelect && !filterClassSelect) return;
            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const role = ((adminObj && adminObj.role) || localStorage.getItem('user_role') || 'teacher').toLowerCase();

            const uniqueGrades = new Set();

            if (role === 'teacher' && (window.currentTeacherGrades && window.currentTeacherGrades.length > 0)) {
                window.currentTeacherGrades.forEach(g => {
                    let rawGrade = (g || '').replace(/Grade /i, '').trim();
                    if (rawGrade) uniqueGrades.add(rawGrade);
                });
            } else if (gradesData && gradesData.length > 0) {
                gradesData.forEach(g => {
                    let rawGrade = (g.name || '').replace(/Grade /i, '').trim();
                    if (rawGrade) uniqueGrades.add(rawGrade.trim());
                });
            }

            const sortedGrades = Array.from(uniqueGrades).sort((a, b) => parseInt(a) - parseInt(b));

            if (gradeSelect) {
                const currentVal = gradeSelect.value;
                gradeSelect.innerHTML = '<option value="">All Grades</option>';
                sortedGrades.forEach(grade => {
                    gradeSelect.innerHTML += `<option value="${grade}">Grade ${grade}</option>`;
                });
                if (currentVal) gradeSelect.value = currentVal;
            }

            if (filterClassSelect) {
                const currentFilterVal = filterClassSelect.value;
                if (role === 'admin') {
                    filterClassSelect.innerHTML = '<option value="">All Classes</option>';
                } else {
                    filterClassSelect.innerHTML = '';
                }
                sortedGrades.forEach(grade => {
                    filterClassSelect.innerHTML += `<option value="${grade}">Grade ${grade}</option>`;
                });
                if (role === 'teacher') {
                    if (currentFilterVal && Array.from(filterClassSelect.options).some(o => o.value === currentFilterVal)) {
                        filterClassSelect.value = currentFilterVal;
                    } else if (filterClassSelect.options.length > 0) {
                        filterClassSelect.selectedIndex = 0;
                    }
                } else {
                    if (currentFilterVal) filterClassSelect.value = currentFilterVal;
                    else filterClassSelect.value = '';
                }
                updateExamResultSubjectsForSelectedClass();
            }
        }

        function filterExamResultStudents() {
            populateExamResultStudentsDropdown();
        }

        async function populateExamResultStudentsDropdown() {
            const gradeVal = document.getElementById('exam-result-grade').value.toLowerCase();
            const studentModalSelect = document.getElementById('exam-result-student');
            if (!studentModalSelect) return;
            studentModalSelect.innerHTML = '<option value="" disabled selected>Select Student</option>';
            
            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const role = ((adminObj && adminObj.role) || localStorage.getItem('user_role') || 'teacher').toLowerCase();

            if (role === 'teacher') {
                await setupTeacherDashboard(adminObj);
            }

            examStudentsList.forEach(user => {
                if (user.student) {
                    let isTeacherGrade = true;
                    if (role === 'teacher' && window.currentTeacherGrades && window.currentTeacherGrades.length > 0) {
                        isTeacherGrade = window.currentTeacherGrades.some(g => isExactGradeMatch(g, user.student.grade));
                    }

                    let matchesSelectedGrade = true;
                    if (gradeVal) {
                        matchesSelectedGrade = isExactGradeMatch(gradeVal, user.student.grade);
                    }

                    if (isTeacherGrade && matchesSelectedGrade) {
                        const displayGrade = user.student.grade ? (String(user.student.grade).toLowerCase().startsWith('grade') ? user.student.grade : `Grade ${user.student.grade}`) : '';
                        studentModalSelect.innerHTML += `<option value="${user.student.id}">${user.name}${displayGrade ? ' (' + displayGrade + ')' : ''}</option>`;
                    }
                }
            });
        }

        let classRankingsData = [];
        async function fetchClassRankingsData() {
            const tbody = document.getElementById('rankings-table-body');
            tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: #697386; padding: 30px;">Loading rankings data...</td></tr>';
            try {
                // Fetch all exam results so we can rank any class
                const res = await apiRequest('/exam-results?all=true');
                if (Array.isArray(res)) {
                    classRankingsData = res;
                } else if (res.data) {
                    classRankingsData = res.data;
                }
                
                // Fetch grades to populate the grade filter if needed
                if (gradesData.length === 0) {
                    const gradesRes = await apiRequest('/admin/grades');
                    if (gradesRes.success && gradesRes.data) {
                        gradesData = gradesRes.data;
                    }
                }
                
                const gradeFilter = document.getElementById('rankings-filter-grade');
                if (gradeFilter) {
                    gradeFilter.innerHTML = '<option value="">Select Grade</option>';
                    
                    const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
                    const role = (localStorage.getItem('user_role') || adminObj.role || 'teacher').toLowerCase();
                    const teacherId = adminObj.teacher ? adminObj.teacher.id : null;
                    const userId = adminObj.id;

                    const uniqueGrades = new Set();

                    if (gradesData && gradesData.length > 0) {
                        gradesData.forEach(g => {
                            let rawGrade = (g.name || '').replace(/Grade /i, '').trim();
                            if (rawGrade) uniqueGrades.add(rawGrade.trim());
                        });
                    }

                    if (classRankingsData && classRankingsData.length > 0) {
                        classRankingsData.forEach(res => {
                            let g = res.student?.grade || res.grade || '';
                            let rawGrade = (g || '').replace(/Grade /i, '').trim();
                            if (rawGrade) uniqueGrades.add(rawGrade.trim());
                        });
                    }

                    if (uniqueGrades.size === 0) {
                        gradeFilter.innerHTML = '<option value="">No Grades Available</option>';
                    } else {
                        Array.from(uniqueGrades).sort((a, b) => parseInt(a) - parseInt(b)).forEach(grade => {
                            gradeFilter.innerHTML += `<option value="${grade}">${grade}</option>`;
                        });
                    }
                }
                
                // Auto-select first available options if not already selected
                const termFilter = document.getElementById('rankings-filter-term');
                const yearFilter = document.getElementById('rankings-filter-year');
                
                if (gradeFilter && gradeFilter.value === '' && gradeFilter.options.length > 1) {
                    gradeFilter.selectedIndex = 1;
                }
                if (termFilter && termFilter.value === '' && termFilter.options.length > 1) {
                    termFilter.selectedIndex = 1;
                }
                if (yearFilter && yearFilter.value === '' && yearFilter.options.length > 1) {
                    yearFilter.selectedIndex = 1;
                }
                
                renderClassRankings();
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="5" style="text-align: center; color: #ff4d4f; padding: 30px;">Failed to load data: ${err.message}</td></tr>`;
            }
        }

        // ─── Admin Teacher Content Library ───────────────────────────────────────
        let adminMaterialsData = [];
        let adminAssignmentsData = [];
        let adminContentActiveTab = 'materials';

        function switchAdminContentTab(tab) {
            adminContentActiveTab = tab;
            const matPane = document.getElementById('admin-content-materials-pane');
            const asnPane = document.getElementById('admin-content-assignments-pane');
            const matBtn  = document.getElementById('admin-content-tab-materials');
            const asnBtn  = document.getElementById('admin-content-tab-assignments');
            const typeWrapper = document.getElementById('admin-content-type-wrapper');

            if (tab === 'materials') {
                matPane.style.display = 'block';
                asnPane.style.display = 'none';
                matBtn.style.background = '#0077be'; matBtn.style.color = 'white';
                asnBtn.style.background = 'rgba(0,0,0,0.06)'; asnBtn.style.color = '#697386';
                if (typeWrapper) typeWrapper.style.display = 'inline-block';
            } else {
                matPane.style.display = 'none';
                asnPane.style.display = 'block';
                asnBtn.style.background = '#0077be'; asnBtn.style.color = 'white';
                matBtn.style.background = 'rgba(0,0,0,0.06)'; matBtn.style.color = '#697386';
                if (typeWrapper) typeWrapper.style.display = 'none';
            }
        }

        async function fetchAdminContent() {
            try {
                const [matRes, asnRes] = await Promise.all([
                    apiRequest('/materials'),
                    apiRequest('/assignments')
                ]);
                adminMaterialsData   = Array.isArray(matRes) ? matRes : (matRes.data || []);
                adminAssignmentsData = Array.isArray(asnRes) ? asnRes : (asnRes.data || []);
                
                await populateAdminContentFilters();
                renderAdminMaterials();
                renderAdminAssignments();
            } catch (err) {
                document.getElementById('admin-materials-table-body').innerHTML =
                    `<tr><td colspan="8" style="text-align:center;color:#ff4d4f;padding:30px;">Error: ${err.message}</td></tr>`;
                document.getElementById('admin-assignments-table-body').innerHTML =
                    `<tr><td colspan="9" style="text-align:center;color:#ff4d4f;padding:30px;">Error: ${err.message}</td></tr>`;
            }
        }

        async function populateAdminContentFilters() {
            const gradeSelect = document.getElementById('admin-content-filter-grade');
            const subjectSelect = document.getElementById('admin-content-filter-subject');
            const teacherSelect = document.getElementById('admin-content-filter-teacher');
            if (!gradeSelect || !subjectSelect || !teacherSelect) return;

            const selectedGrade = gradeSelect.value;
            const selectedSubject = subjectSelect.value;
            const selectedTeacher = teacherSelect.value;

            // Fetch system grades, subjects, and teachers if available
            const [gradesRes, subjectsRes, teachersRes] = await Promise.allSettled([
                apiRequest('/admin/grades'),
                apiRequest('/subjects'),
                apiRequest('/admin/teachers')
            ]);

            const gradesSet = new Set();
            if (gradesRes.status === 'fulfilled' && gradesRes.value && gradesRes.value.data) {
                gradesRes.value.data.forEach(g => {
                    const val = g.name || g.grade_name || g.grade;
                    if (val) gradesSet.add(String(val).trim());
                });
            }

            const subjectsSet = new Set();
            if (subjectsRes.status === 'fulfilled' && subjectsRes.value && subjectsRes.value.data) {
                subjectsRes.value.data.forEach(s => {
                    const val = s.subject_name || s.name;
                    if (val) subjectsSet.add(String(val).trim());
                });
            }

            const teachersSet = new Set();
            if (teachersRes.status === 'fulfilled' && teachersRes.value && teachersRes.value.data) {
                teachersRes.value.data.forEach(u => {
                    const val = u.name || (u.user && u.user.name);
                    if (val) teachersSet.add(String(val).trim());
                });
            }

            // Also collect from actual materials and assignments data
            [...adminMaterialsData, ...adminAssignmentsData].forEach(item => {
                const g = item.subject?.grade || item.grade;
                if (g) gradesSet.add(String(g).trim());

                const s = item.subject?.subject_name || item.subject?.name || item.subject_name;
                if (s) subjectsSet.add(String(s).trim());

                const t = item.teacher?.user?.name || item.teacher?.name || item.teacher_name;
                if (t) teachersSet.add(String(t).trim());
            });

            // Populate grades (sorted by grade number if possible)
            const sortedGrades = Array.from(gradesSet).sort((a, b) => {
                const numA = parseInt(String(a).replace(/\D+/g, '')) || 0;
                const numB = parseInt(String(b).replace(/\D+/g, '')) || 0;
                if (numA !== numB) return numA - numB;
                return String(a).localeCompare(String(b));
            });
            gradeSelect.innerHTML = '<option value="">All Grades</option>' +
                sortedGrades.map(g => `<option value="${g}">${g}</option>`).join('');
            if (selectedGrade && sortedGrades.includes(selectedGrade)) {
                gradeSelect.value = selectedGrade;
            }

            // Populate subjects (alphabetical)
            const sortedSubjects = Array.from(subjectsSet).sort((a, b) => String(a).localeCompare(String(b)));
            subjectSelect.innerHTML = '<option value="">All Subjects</option>' +
                sortedSubjects.map(s => `<option value="${s}">${s}</option>`).join('');
            if (selectedSubject && sortedSubjects.includes(selectedSubject)) {
                subjectSelect.value = selectedSubject;
            }

            // Populate teachers (alphabetical)
            const sortedTeachers = Array.from(teachersSet).sort((a, b) => String(a).localeCompare(String(b)));
            teacherSelect.innerHTML = '<option value="">All Teachers</option>' +
                sortedTeachers.map(t => `<option value="${t}">${t}</option>`).join('');
            if (selectedTeacher && sortedTeachers.includes(selectedTeacher)) {
                teacherSelect.value = selectedTeacher;
            }
        }

        function filterAdminContent() {
            renderAdminMaterials();
            renderAdminAssignments();
        }

        function renderAdminMaterials() {
            const tbody   = document.getElementById('admin-materials-table-body');
            if (!tbody) return;
            const search  = (document.getElementById('admin-content-search')?.value || '').toLowerCase().trim();
            const grade   = (document.getElementById('admin-content-filter-grade')?.value || '').trim();
            const subject = (document.getElementById('admin-content-filter-subject')?.value || '').trim();
            const teacher = (document.getElementById('admin-content-filter-teacher')?.value || '').trim();
            const type    = (document.getElementById('admin-materials-type')?.value || '').trim();

            const filtered = adminMaterialsData.filter(m => {
                const mTeacher = m.teacher?.user?.name || m.teacher?.name || '';
                const mSubject = m.subject?.subject_name || m.subject?.name || '';
                const mGrade   = m.subject?.grade || m.grade || '';
                const mTitle   = m.title || '';
                const mDesc    = m.description || '';

                if (grade && !isExactGradeMatch(grade, mGrade) && grade.toLowerCase() !== mGrade.toLowerCase()) {
                    return false;
                }
                if (subject && mSubject.toLowerCase() !== subject.toLowerCase()) {
                    return false;
                }
                if (teacher && mTeacher.toLowerCase() !== teacher.toLowerCase()) {
                    return false;
                }
                if (type && (m.type || '').toLowerCase() !== type.toLowerCase()) {
                    return false;
                }
                if (search) {
                    const match = mTeacher.toLowerCase().includes(search) ||
                                  mSubject.toLowerCase().includes(search) ||
                                  mGrade.toLowerCase().includes(search) ||
                                  mTitle.toLowerCase().includes(search) ||
                                  (m.topic || '').toLowerCase().includes(search) ||
                                  mDesc.toLowerCase().includes(search);
                    if (!match) return false;
                }
                return true;
            });

            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;color:#697386;padding:30px;">No materials match the selected filters.</td></tr>';
                return;
            }

            const typeColors = { pdf:'#e53935', video:'#7b1fa2', image:'#0288d1', assignment:'#f57c00' };

            tbody.innerHTML = filtered.map(m => {
                const tName   = m.teacher?.user?.name || m.teacher?.name || '—';
                const sName   = m.subject?.subject_name || m.subject?.name || '—';
                const gName   = m.subject?.grade || m.grade || '—';
                const topicName = m.topic || m.title || '—';
                const typeBadge = `<span style="background:${typeColors[m.type] || '#697386'};color:white;font-size:10px;font-weight:700;padding:2px 7px;border-radius:10px;">${(m.type||'').toUpperCase()}</span>`;
                const uploaded  = m.created_at ? new Date(m.created_at).toLocaleDateString() : '—';
                const fileLink  = m.file_url
                    ? `<a href="${getFileUrl(m.file_url)}" target="_blank" style="color:#0077be;font-weight:600;font-size:12px;">View ↗</a>`
                    : '—';
                return `<tr>
                    <td style="font-weight:600;">${tName}</td>
                    <td>${sName}</td>
                    <td>${gName}</td>
                    <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${topicName}">${topicName}</td>
                    <td>${typeBadge}</td>
                    <td>${uploaded}</td>
                    <td>${fileLink}</td>
                    <td>
                        <button class="btn-toggle-fee unpay" onclick="deleteAdminMaterial('${m.id}')" style="padding: 4px 10px; font-size: 11px; background: #ff4d4f; border: none; border-radius: 6px; color: white; cursor: pointer;">
                            Delete
                        </button>
                    </td>
                </tr>`;
            }).join('');
        }

        function renderAdminAssignments() {
            const tbody   = document.getElementById('admin-assignments-table-body');
            if (!tbody) return;
            const search  = (document.getElementById('admin-content-search')?.value || '').toLowerCase().trim();
            const grade   = (document.getElementById('admin-content-filter-grade')?.value || '').trim();
            const subject = (document.getElementById('admin-content-filter-subject')?.value || '').trim();
            const teacher = (document.getElementById('admin-content-filter-teacher')?.value || '').trim();

            const filtered = adminAssignmentsData.filter(a => {
                const aTeacher = a.teacher?.user?.name || a.teacher?.name || '';
                const aSubject = a.subject?.subject_name || a.subject?.name || '';
                const aGrade   = a.subject?.grade || a.grade || '';
                const aTitle   = a.title || '';
                const aDesc    = a.description || '';

                if (grade && !isExactGradeMatch(grade, aGrade) && grade.toLowerCase() !== aGrade.toLowerCase()) {
                    return false;
                }
                if (subject && aSubject.toLowerCase() !== subject.toLowerCase()) {
                    return false;
                }
                if (teacher && aTeacher.toLowerCase() !== teacher.toLowerCase()) {
                    return false;
                }
                if (search) {
                    const match = aTeacher.toLowerCase().includes(search) ||
                                  aSubject.toLowerCase().includes(search) ||
                                  aGrade.toLowerCase().includes(search) ||
                                  aTitle.toLowerCase().includes(search) ||
                                  (a.topic || '').toLowerCase().includes(search) ||
                                  aDesc.toLowerCase().includes(search);
                    if (!match) return false;
                }
                return true;
            });

            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;color:#697386;padding:30px;">No assignments match the selected filters.</td></tr>';
                return;
            }

            tbody.innerHTML = filtered.map(a => {
                const tName    = a.teacher?.user?.name || a.teacher?.name || '—';
                const sName    = a.subject?.subject_name || a.subject?.name || '—';
                const gName    = a.subject?.grade || a.grade || '—';
                const dueDate  = a.due_date ? new Date(a.due_date).toLocaleDateString() : '—';
                const marks    = a.total_marks != null ? a.total_marks : '—';
                const uploaded = a.created_at ? new Date(a.created_at).toLocaleDateString() : '—';
                const fileLink = a.file_url
                    ? `<a href="${getFileUrl(a.file_url)}" target="_blank" style="color:#0077be;font-weight:600;font-size:12px;">View ↗</a>`
                    : '—';
                return `<tr>
                    <td style="font-weight:600;">${tName}</td>
                    <td>${sName}</td>
                    <td>${gName}</td>
                    <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${a.title}">${a.title}</td>
                    <td>${dueDate}</td>
                    <td>${marks}</td>
                    <td>${uploaded}</td>
                    <td>${fileLink}</td>
                    <td>
                        <button class="btn-toggle-fee unpay" onclick="deleteAdminAssignment('${a.id}')" style="padding: 4px 10px; font-size: 11px; background: #ff4d4f; border: none; border-radius: 6px; color: white; cursor: pointer;">
                            Delete
                        </button>
                    </td>
                </tr>`;
            }).join('');
        }

        async function deleteAdminMaterial(id) {
            if (!confirm('Are you sure you want to delete this material?')) return;
            try {
                if (String(id).startsWith('subject_')) {
                    const subId = id.replace('subject_', '');
                    const res = await apiRequest(`/subjects/${subId}?field=pdf`, { method: 'DELETE' });
                    showToast(res.message || 'Material deleted successfully', 'success');
                } else {
                    const res = await apiRequest(`/materials/${id}`, { method: 'DELETE' });
                    showToast(res.message || 'Material deleted successfully', 'success');
                }
                fetchAdminContent();
            } catch (err) {
                showToast(err.message || 'Failed to delete material', 'error');
            }
        }

        async function deleteAdminAssignment(id) {
            if (!confirm('Are you sure you want to delete this assignment?')) return;
            try {
                if (String(id).startsWith('subject_')) {
                    const subId = id.replace('subject_', '');
                    const res = await apiRequest(`/subjects/${subId}?field=assignment`, { method: 'DELETE' });
                    showToast(res.message || 'Assignment deleted successfully', 'success');
                } else {
                    const res = await apiRequest(`/assignments/${id}`, { method: 'DELETE' });
                    showToast(res.message || 'Assignment deleted successfully', 'success');
                }
                fetchAdminContent();
            } catch (err) {
                showToast(err.message || 'Failed to delete assignment', 'error');
            }
        }

        function renderClassRankings() {
            const tbody = document.getElementById('rankings-table-body');
            const termFilter = document.getElementById('rankings-filter-term').value;
            const yearFilter = document.getElementById('rankings-filter-year').value;
            const gradeFilter = document.getElementById('rankings-filter-grade').value.toLowerCase();

            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const role = ((adminObj && adminObj.role) || localStorage.getItem('user_role') || 'teacher').toLowerCase();

            if (role !== 'admin') {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #697386; padding: 30px;">Class Rankings are accessible by administrators only.</td></tr>';
                return;
            }

            if (!termFilter || !yearFilter || !gradeFilter) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #697386; padding: 30px;">Please select a Grade, Term, and Year to view rankings.</td></tr>';
                return;
            }

            // Filter results for the selected term, year, and grade
            const filteredResults = classRankingsData.filter(res => {
                const matchesTerm = res.term === termFilter;
                const matchesYear = String(res.academic_year) === yearFilter;
                const matchesGrade = isExactGradeMatch(gradeFilter, res.student ? res.student.grade : null);
                return matchesTerm && matchesYear && matchesGrade;
            });

            if (filteredResults.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #697386; padding: 30px;">No exam results found for this Grade, Term, and Year.</td></tr>';
                return;
            }

            // Group by student and calculate total marks
            const studentTotals = {};
            filteredResults.forEach(res => {
                if (!res.student || !res.student.user) return;

                const studentId = res.student.id;
                if (!studentTotals[studentId]) {
                    const rawG = res.student.grade || '';
                    const displayGrade = rawG ? (rawG.toLowerCase().startsWith('grade') ? rawG : `Grade ${rawG}`) : '—';

                    studentTotals[studentId] = {
                        id: studentId,
                        name: res.student.user.name,
                        gradeClass: displayGrade,
                        totalMarks: 0,
                        subjectCount: 0,
                        results: []
                    };
                }
                studentTotals[studentId].totalMarks += res.marks_obtained;
                studentTotals[studentId].subjectCount += 1;
                studentTotals[studentId].results.push(res);
            });

            // Convert to array and sort by totalMarks descending
            const rankingsArray = Object.values(studentTotals).sort((a, b) => b.totalMarks - a.totalMarks);
            window.currentRankingsArray = rankingsArray;

            tbody.innerHTML = '';
            rankingsArray.forEach((student, index) => {
                const rank = index + 1;
                let rankBadge = '';
                if (rank === 1) rankBadge = '<span class="badge" style="background:#FFD700; color:#856404; padding:6px 10px; font-weight:bold; font-size:12px;">🥇 1st Place</span>';
                else if (rank === 2) rankBadge = '<span class="badge" style="background:#C0C0C0; color:#383d41; padding:6px 10px; font-weight:bold; font-size:12px;">🥈 2nd Place</span>';
                else if (rank === 3) rankBadge = '<span class="badge" style="background:#CD7F32; color:#fff; padding:6px 10px; font-weight:bold; font-size:12px;">🥉 3rd Place</span>';
                else rankBadge = `<span style="font-weight:700; color:#697386; font-size:13px;">${rank}th Place</span>`;

                const avg = (student.totalMarks / (student.subjectCount || 1)).toFixed(1);

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="text-align: center;">${rankBadge}</td>
                    <td style="font-weight:600; color:#1a1f36;">${student.name}</td>
                    <td style="font-weight:600; color:#0077be;">${student.gradeClass}</td>
                    <td style="font-weight:bold; color:#4CAF50;">${student.totalMarks}</td>
                    <td style="font-weight:bold; color:#ff9800;">${avg}%</td>
                    <td>${student.subjectCount} Subjects</td>
                    <td>
                        <button class="btn-toggle-fee pay" onclick="openRankingStudentDetailModal(${student.id})" style="padding:4px 10px; font-size:11px; width:auto; border:none; background:#0077be; color:white; cursor:pointer;">
                            View Subject Marks
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function openRankingStudentDetailModal(studentId) {
            const student = (window.currentRankingsArray || []).find(s => s.id === studentId);
            if (!student) return;

            document.getElementById('ranking-modal-title').innerText = `${student.name} - ${student.gradeClass}`;
            document.getElementById('ranking-modal-rank').innerText = `${(window.currentRankingsArray.findIndex(s => s.id === studentId) + 1)} Place`;
            document.getElementById('ranking-modal-score').innerText = `${student.totalMarks} Marks`;
            const avg = (student.totalMarks / (student.subjectCount || 1)).toFixed(1);
            document.getElementById('ranking-modal-avg').innerText = `${avg}%`;

            const tbody = document.getElementById('ranking-student-detail-body');
            tbody.innerHTML = '';

            student.results.forEach(res => {
                const subName = res.subject ? res.subject.subject_name : 'Subject';
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="font-weight:600;">${subName}</td>
                    <td>${res.exam_name || 'Exam'}</td>
                    <td style="font-weight:bold; color:#4CAF50;">${res.marks_obtained} / ${res.total_marks}</td>
                    <td><span class="badge" style="background:#ff9800; color:white; font-size:11px; font-weight:bold;">${res.grade}</span></td>
                    <td>${res.remarks || '—'}</td>
                `;
                tbody.appendChild(tr);
            });

            document.getElementById('ranking-student-detail-modal').classList.remove('hidden');
        }

        function closeRankingStudentDetailModal() {
            document.getElementById('ranking-student-detail-modal').classList.add('hidden');
        }

        // ─── TEACHER MY CLASS RESULTS FUNCTIONS ───────────────────────────
        let teacherClassResultsData = [];
        let teacherClassStudentsList = [];
        let teacherClassSubjectsList = [];

        async function fetchTeacherClassResults() {
            const container = document.getElementById('teacher-class-results-container');
            if (container) {
                container.innerHTML = '<div style="text-align: center; color: #697386; padding: 40px;">Loading student class results...</div>';
            }

            try {
                const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
                const role = ((adminObj && adminObj.role) || localStorage.getItem('user_role') || 'teacher').toLowerCase();

                if (role === 'teacher') {
                    await setupTeacherDashboard(adminObj);
                }
                const [resResults, resUsers, resSubjects, resGrades] = await Promise.all([
                    apiRequest('/exam-results?all=true'),
                    apiRequest('/admin/users?all=true'),
                    apiRequest('/subjects'),
                    apiRequest('/admin/grades')
                ]);

                if (Array.isArray(resResults)) {
                    teacherClassResultsData = resResults;
                } else if (resResults && resResults.data) {
                    teacherClassResultsData = resResults.data;
                }

                if (resUsers && resUsers.success && resUsers.data) {
                    teacherClassStudentsList = resUsers.data.filter(u => u.role && u.role.toLowerCase() === 'student' && u.student);
                }

                if (resSubjects && resSubjects.success && resSubjects.data) {
                    teacherClassSubjectsList = resSubjects.data;
                }

                // Determine Class Teacher assigned grade(s) from Grades Management
                const teacherId = adminObj.teacher ? adminObj.teacher.id : null;
                const userId = adminObj.id;

                const teacherAssignedGrades = new Set();

                if (resGrades && resGrades.success && Array.isArray(resGrades.data)) {
                    resGrades.data.forEach(g => {
                        if (isClassTeacherOfGrade(g, adminObj) && g.name) {
                            teacherAssignedGrades.add(g.name.trim());
                        }
                    });
                }

                // Do NOT fallback for My Class Results if not explicitly assigned in Grades Management
                window.currentTeacherClassGrades = Array.from(teacherAssignedGrades);

                // Populate Grade Filter for teacher
                const gradeSelect = document.getElementById('teacher-class-results-grade');
                if (gradeSelect) {
                    gradeSelect.innerHTML = '';
                    if (teacherAssignedGrades.size > 0) {
                        Array.from(teacherAssignedGrades).forEach(g => {
                            const cleanG = g.toLowerCase().startsWith('grade') ? g : `Grade ${g}`;
                            const opt = document.createElement('option');
                            opt.value = g;
                            opt.textContent = cleanG;
                            gradeSelect.appendChild(opt);
                        });
                    } else {
                        const opt = document.createElement('option');
                        opt.value = '';
                        opt.textContent = 'All Assigned Grades';
                        gradeSelect.appendChild(opt);
                    }
                }

                renderTeacherClassResults();
            } catch (err) {
                console.error("Error loading teacher class results:", err);
                if (container) {
                    container.innerHTML = `<div style="text-align: center; color: #ff4d4f; padding: 40px;">Failed to load class results: ${err.message}</div>`;
                }
            }
        }

        function renderTeacherClassResults() {
            const container = document.getElementById('teacher-class-results-container');
            if (!container) return;
            container.innerHTML = '';

            const searchVal = (document.getElementById('teacher-class-results-search')?.value || '').toLowerCase();
            const gradeVal = document.getElementById('teacher-class-results-grade')?.value || '';
            const termVal = document.getElementById('teacher-class-results-term')?.value || 'Term 1';
            const yearVal = document.getElementById('teacher-class-results-year')?.value || '2026';

            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const role = ((adminObj && adminObj.role) || localStorage.getItem('user_role') || 'teacher').toLowerCase();

            if (role === 'teacher') {
                const hasAssignedClass = (window.currentTeacherClassGrades && window.currentTeacherClassGrades.length > 0);
                if (!hasAssignedClass) {
                    container.innerHTML = `
                        <div class="glass-card" style="text-align: center; color: #697386; padding: 60px 20px; border-radius: 14px; background: white;">
                            <div style="font-size:42px; margin-bottom:12px;">🏫</div>
                            <h3 style="margin: 0 0 8px 0; color: #1a1f36; font-size:18px;">You do not have an assigned class</h3>
                            <p style="margin: 0; font-size: 14px; color: #697386; max-width:450px; margin:auto;">You are currently not assigned as a class teacher to any grade in Grades Management. Please contact the administration to assign a class to you.</p>
                        </div>
                    `;
                    return;
                }
            }

            // 1. Filter students belonging to teacher's assigned class in Grades Management
            const filteredStudents = teacherClassStudentsList.filter(user => {
                if (!user.student) return false;
                
                let isTeacherGrade = true;
                if (role === 'teacher') {
                    if (window.currentTeacherClassGrades && window.currentTeacherClassGrades.length > 0) {
                        isTeacherGrade = window.currentTeacherClassGrades.some(g => isExactGradeMatch(g, user.student.grade));
                    } else {
                        isTeacherGrade = false;
                    }
                }

                let matchesSelectedGrade = true;
                if (gradeVal) {
                    matchesSelectedGrade = isExactGradeMatch(gradeVal, user.student.grade);
                }

                const matchesSearch = !searchVal || (user.name || '').toLowerCase().includes(searchVal);

                return isTeacherGrade && matchesSelectedGrade && matchesSearch;
            });

            if (filteredStudents.length === 0) {
                container.innerHTML = '<div class="glass-card" style="text-align: center; color: #697386; padding: 40px; border-radius:12px;">No students found matching your criteria in your assigned class.</div>';
                return;
            }

            // 2. Process each student's results for the selected term & year
            const studentDataList = filteredStudents.map(user => {
                const studentId = user.student.id;
                const rawG = user.student.grade || '';
                const displayGrade = rawG ? (rawG.toLowerCase().startsWith('grade') ? rawG : `Grade ${rawG}`) : '—';

                const results = teacherClassResultsData.filter(res => {
                    const matchesStudent = res.student_id === studentId;
                    const matchesTerm = !termVal || res.term === termVal;
                    const matchesYear = !yearVal || String(res.academic_year) === yearVal;
                    return matchesStudent && matchesTerm && matchesYear;
                });

                let totalMarks = 0;
                results.forEach(r => {
                    totalMarks += Number(r.marks_obtained || 0);
                });

                const subjectCount = results.length;
                const avg = subjectCount > 0 ? (totalMarks / subjectCount).toFixed(1) : 0;

                return {
                    user,
                    studentId,
                    name: user.name,
                    grade: displayGrade,
                    results,
                    totalMarks,
                    subjectCount,
                    avg: Number(avg)
                };
            });

            // 3. Sort students by totalMarks descending to determine class rank
            studentDataList.sort((a, b) => b.totalMarks - a.totalMarks);
            window.currentTeacherClassStudentsData = studentDataList;

            // 4. Render student report cards
            studentDataList.forEach((st, idx) => {
                const rank = idx + 1;
                let rankBadge = '';
                if (rank === 1) rankBadge = '<span class="badge" style="background:#FFD700; color:#856404; padding:6px 12px; font-weight:bold; font-size:13px; border-radius:20px;">🥇 1st Place</span>';
                else if (rank === 2) rankBadge = '<span class="badge" style="background:#C0C0C0; color:#383d41; padding:6px 12px; font-weight:bold; font-size:13px; border-radius:20px;">🥈 2nd Place</span>';
                else if (rank === 3) rankBadge = '<span class="badge" style="background:#CD7F32; color:#fff; padding:6px 12px; font-weight:bold; font-size:13px; border-radius:20px;">🥉 3rd Place</span>';
                else rankBadge = `<span class="badge" style="background:#eef2f5; color:#697386; padding:6px 12px; font-weight:bold; font-size:13px; border-radius:20px;">Rank #${rank}</span>`;

                const card = document.createElement('div');
                card.className = 'glass-card';
                card.style.padding = '24px';
                card.style.marginBottom = '20px';
                card.style.borderRadius = '14px';
                card.style.background = 'white';

                let rowsHtml = '';
                if (st.results.length === 0) {
                    rowsHtml = '<tr><td colspan="5" style="text-align: center; color: #697386; padding: 20px;">No exam results recorded for this term.</td></tr>';
                } else {
                    rowsHtml = st.results.map(r => {
                        const subName = r.subject ? r.subject.subject_name : 'Subject';
                        return `
                            <tr>
                                <td style="font-weight: 600; color:#1a1f36;">${subName}</td>
                                <td>${r.exam_name || 'Exam'}</td>
                                <td style="font-weight: bold; color: #4CAF50;">${r.marks_obtained} / ${r.total_marks}</td>
                                <td><span class="badge" style="background:#ff9800; color:white; font-size:12px; font-weight:bold; padding:3px 8px; border-radius:4px;">${r.grade}</span></td>
                                <td style="color:#697386;">${r.remarks || '—'}</td>
                            </tr>
                        `;
                    }).join('');
                }

                card.innerHTML = `
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; padding-bottom:14px; border-bottom:1px solid rgba(0,0,0,0.06);">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:44px; height:44px; border-radius:50%; background:#0077be; color:white; font-weight:bold; font-size:18px; display:flex; align-items:center; justify-content:center;">
                                ${(st.name || 'S').substring(0, 1).toUpperCase()}
                            </div>
                            <div>
                                <h3 style="margin:0; font-size:17px; color:#1a1f36;">${st.name}</h3>
                                <span style="font-size:12px; color:#697386; font-weight:600;">${st.grade} &bull; ${st.subjectCount} Subjects Recorded</span>
                            </div>
                        </div>
                        <div style="display:flex; align-items:center; gap:15px;">
                            <div>${rankBadge}</div>
                            <div style="text-align:right;">
                                <div style="font-size:18px; font-weight:bold; color:#4CAF50;">${st.totalMarks} Marks</div>
                                <div style="font-size:12px; font-weight:bold; color:#ff9800;">Average: ${st.avg}%</div>
                            </div>
                            <button class="btn-toggle-fee pay" onclick="printSingleStudentReportCard(${st.studentId})" style="padding:6px 12px; font-size:12px; font-weight:600; border:none; background:#0077be; color:white; border-radius:6px; cursor:pointer;">
                                Print Report Card
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Exam Name</th>
                                    <th>Marks Obtained</th>
                                    <th>Grade</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${rowsHtml}
                            </tbody>
                        </table>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function printTeacherClassResultsPDF() {
            const list = window.currentTeacherClassStudentsData || [];
            if (list.length === 0) {
                showToast('No class results available to print.', 'error');
                return;
            }

            const termVal = document.getElementById('teacher-class-results-term')?.value || 'Term 1';
            const yearVal = document.getElementById('teacher-class-results-year')?.value || '2026';
            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const teacherName = adminObj.name || 'Teacher';

            const printWindow = window.open('', '_blank');
            let htmlContent = `
                <html>
                <head>
                    <title>Class Exam Results Sheet - ${termVal} ${yearVal}</title>
                    <style>
                        body { font-family: sans-serif; padding: 30px; color: #1a1f36; }
                        h1 { color: #0077be; text-align: center; margin-bottom: 4px; font-size: 24px; }
                        h2 { text-align: center; color: #697386; font-weight: 500; font-size: 15px; margin: 0 0 25px 0; }
                        .meta-info { display: flex; justify-content: space-between; margin-bottom: 20px; background: #f8fafc; padding: 12px 18px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 13px; }
                        .student-block { margin-bottom: 30px; page-break-inside: avoid; border: 1px solid #e3e8ee; border-radius: 8px; padding: 15px; }
                        .student-header { display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #e3e8ee; padding-bottom: 8px; font-weight: bold; }
                        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
                        th, td { padding: 8px 12px; border: 1px solid #e3e8ee; text-align: left; font-size: 12px; }
                        th { background-color: #f7fafc; font-weight: 600; color: #4f566b; }
                        .badge { padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: bold; }
                    </style>
                </head>
                <body>
                    <h1>Smart School - Class Results Report Sheet</h1>
                    <h2>${termVal} &bull; ${yearVal}</h2>
                    <div class="meta-info">
                        <div><strong>Teacher:</strong> ${teacherName}</div>
                        <div><strong>Total Students:</strong> ${list.length}</div>
                        <div><strong>Date Generated:</strong> ${new Date().toLocaleDateString()}</div>
                    </div>
            `;

            list.forEach((st, idx) => {
                const rank = idx + 1;
                htmlContent += `
                    <div class="student-block">
                        <div class="student-header">
                            <div>Rank #${rank} &bull; ${st.name} (${st.grade})</div>
                            <div>Total: ${st.totalMarks} Marks | Avg: ${st.avg}%</div>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Exam Name</th>
                                    <th>Marks Obtained</th>
                                    <th>Grade</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${st.results.map(r => `
                                    <tr>
                                        <td>${r.subject ? r.subject.subject_name : 'Subject'}</td>
                                        <td>${r.exam_name || 'Exam'}</td>
                                        <td><strong>${r.marks_obtained} / ${r.total_marks}</strong></td>
                                        <td><span class="badge" style="background:#ff9800; color:white;">${r.grade}</span></td>
                                        <td>${r.remarks || '—'}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            });

            htmlContent += `
                    <script>
                        window.onload = function() { window.print(); setTimeout(() => window.close(), 500); };
                    <\/script>
                </body>
                </html>
            `;

            printWindow.document.write(htmlContent);
            printWindow.document.close();
        }

        function printSingleStudentReportCard(studentId) {
            const list = window.currentTeacherClassStudentsData || [];
            const st = list.find(s => s.studentId === studentId);
            if (!st) return;

            const termVal = document.getElementById('teacher-class-results-term')?.value || 'Term 1';
            const yearVal = document.getElementById('teacher-class-results-year')?.value || '2026';
            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const teacherName = adminObj.name || 'Teacher';

            const printWindow = window.open('', '_blank');
            const htmlContent = `
                <html>
                <head>
                    <title>Student Report Card - ${st.name}</title>
                    <style>
                        body { font-family: sans-serif; padding: 40px; color: #1a1f36; max-width: 800px; margin: auto; }
                        .header { text-align: center; border-bottom: 2px solid #0077be; padding-bottom: 15px; margin-bottom: 25px; }
                        h1 { color: #0077be; margin: 0 0 5px 0; font-size: 26px; }
                        h2 { color: #697386; margin: 0; font-size: 16px; font-weight: 500; }
                        .student-info { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; background: #f8fafc; padding: 15px 20px; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 25px; }
                        .info-item { font-size: 14px; }
                        .info-item label { color: #697386; font-size: 12px; display: block; margin-bottom: 2px; }
                        .info-item strong { color: #1a1f36; font-size: 15px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 30px; }
                        th, td { padding: 12px 15px; border: 1px solid #e2e8f0; text-align: left; font-size: 13px; }
                        th { background-color: #f1f5f9; font-weight: bold; color: #334155; }
                        .signatures { display: flex; justify-content: space-between; margin-top: 60px; padding-top: 20px; border-top: 1px solid #e2e8f0; }
                        .sig-box { text-align: center; width: 200px; }
                        .sig-line { border-top: 1px solid #94a3b8; margin-bottom: 8px; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>Smart School System</h1>
                        <h2>Official Student Academic Progress Report Sheet</h2>
                    </div>

                    <div class="student-info">
                        <div class="info-item"><label>Student Name</label><strong>${st.name}</strong></div>
                        <div class="info-item"><label>Class / Grade</label><strong>${st.grade}</strong></div>
                        <div class="info-item"><label>Term & Academic Year</label><strong>${termVal} (${yearVal})</strong></div>
                        <div class="info-item"><label>Class Rank Position</label><strong style="color:#0077be;">${(list.findIndex(s => s.studentId === studentId) + 1)} Place</strong></div>
                        <div class="info-item"><label>Total Marks Obtained</label><strong style="color:#4CAF50;">${st.totalMarks} Marks</strong></div>
                        <div class="info-item"><label>Average Score</label><strong style="color:#ff9800;">${st.avg}%</strong></div>
                    </div>

                    <h3>Subject Marks & Evaluation</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Subject Name</th>
                                <th>Exam Name</th>
                                <th>Marks Obtained</th>
                                <th>Grade</th>
                                <th>Teacher Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${st.results.map(r => `
                                <tr>
                                    <td style="font-weight:bold;">${r.subject ? r.subject.subject_name : 'Subject'}</td>
                                    <td>${r.exam_name || 'Exam'}</td>
                                    <td style="font-weight:bold; color:#0077be;">${r.marks_obtained} / ${r.total_marks}</td>
                                    <td><strong>${r.grade}</strong></td>
                                    <td>${r.remarks || '—'}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>

                    <div class="signatures">
                        <div class="sig-box">
                            <div class="sig-line"></div>
                            <span style="font-size:12px; color:#64748b;">Class Teacher (${teacherName})</span>
                        </div>
                        <div class="sig-box">
                            <div class="sig-line"></div>
                            <span style="font-size:12px; color:#64748b;">Principal Signature</span>
                        </div>
                    </div>

                    <script>
                        window.onload = function() { window.print(); setTimeout(() => window.close(), 500); };
                    <\/script>
                </body>
                </html>
            `;

            printWindow.document.write(htmlContent);
            printWindow.document.close();
        }

        function printClassRankingsPDF() {
            const termFilter = document.getElementById('rankings-filter-term').value;
            const yearFilter = document.getElementById('rankings-filter-year').value;

            if (!termFilter || !yearFilter) {
                showToast('Please select a Term and Year before printing.', 'error');
                return;
            }

            const printWindow = window.open('', '_blank');
            const tableHTML = document.getElementById('rankings-table-body').innerHTML;
            
            const html = `
                <html>
                <head>
                    <title>Class Rankings Report</title>
                    <style>
                        body { font-family: 'Inter', sans-serif; padding: 40px; color: #1a1f36; }
                        h1 { color: #0077be; text-align: center; margin-bottom: 5px; }
                        h2 { text-align: center; color: #697386; font-weight: 500; font-size: 16px; margin-bottom: 30px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { padding: 12px 15px; border-bottom: 1px solid #e3e8ee; text-align: left; }
                        th { background-color: #f7fafc; font-weight: 600; color: #4f566b; }
                        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
                    </style>
                </head>
                <body>
                    <h1>Smart School - Class Rankings</h1>
                    <h2>${termFilter} - ${yearFilter}</h2>
                    <table>
                        <thead>
                            <tr>
                                <th style="text-align: center;">Place</th>
                                <th>Student Name</th>
                                <th>Grade (Class)</th>
                                <th>Total Marks</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${tableHTML}
                        </tbody>
                    </table>
                    <div style="margin-top: 50px; text-align: right; color: #697386; font-size: 12px;">
                        Generated on: ${new Date().toLocaleString()}
                    </div>
                </body>
                </html>
            `;
            
            printWindow.document.write(html);
            printWindow.document.close();
            
            setTimeout(() => {
                printWindow.print();
            }, 500);
        }

        function getFilteredExamResults() {
            const searchVal = document.getElementById('exam-results-search').value.toLowerCase();
            const classFilterVal = document.getElementById('exam-results-filter-class')?.value;
            const subjectFilterVal = document.getElementById('exam-results-filter-subject').value;
            const termFilterVal = document.getElementById('exam-results-filter-term').value;
            const yearFilterVal = document.getElementById('exam-results-filter-year').value;

            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const role = (localStorage.getItem('user_role') || adminObj.role || 'teacher').toLowerCase();

            return examResultsData.filter(res => {
                if (role === 'teacher') {
                    if (!isExamResultAccessibleToTeacher(res)) {
                        return false;
                    }
                }

                const studentName = (res.student && res.student.user ? res.student.user.name : '').toLowerCase();
                const matchesSearch = studentName.includes(searchVal);
                const matchesClass = !classFilterVal || isExactGradeMatch(classFilterVal, res.student ? res.student.grade : null);
                const matchesSubject = !subjectFilterVal || 
                    String(res.subject_id) === String(subjectFilterVal) ||
                    (res.subject && (String(res.subject.id) === String(subjectFilterVal) || (res.subject.subject_name && res.subject.subject_name.toLowerCase() === String(subjectFilterVal).toLowerCase())));
                const matchesTerm = !termFilterVal || res.term === termFilterVal;
                const matchesYear = !yearFilterVal || String(res.academic_year) === String(yearFilterVal);

                return matchesSearch && matchesClass && matchesSubject && matchesTerm && matchesYear;
            });
        }

        function exportExamResultsCSV() {
            const filtered = getFilteredExamResults();
            if (filtered.length === 0) {
                showToast('No records to export.', 'error');
                return;
            }

            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "Student,Class,Subject,Exam Name,Term,Year,Marks Obtained,Total Marks,Grade,Remarks\n";

            filtered.forEach(res => {
                const studentName = res.student && res.student.user ? res.student.user.name : 'Unknown Student';
                let studentGradeClass = '—';
                if (res.student && res.student.grade) {
                    const rawG = String(res.student.grade).trim();
                    studentGradeClass = rawG.toLowerCase().startsWith('grade') ? rawG : `Grade ${rawG}`;
                }

                const subjectName = res.subject ? res.subject.subject_name : 'Unknown Subject';
                const remarks = res.remarks || '';
                
                const cleanStudent = studentName.replace(/"/g, '""');
                const cleanClass = studentGradeClass.replace(/"/g, '""');
                const cleanSubject = subjectName.replace(/"/g, '""');
                const cleanExam = (res.exam_name || '').replace(/"/g, '""');
                const cleanRemarks = remarks.replace(/"/g, '""');

                csvContent += `"${cleanStudent}","${cleanClass}","${cleanSubject}","${cleanExam}","${res.term}",${res.academic_year},${res.marks_obtained},${res.total_marks},"${res.grade}","${cleanRemarks}"\n`;
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `exam_results_report_${new Date().toISOString().slice(0,10)}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function printExamResultsPDF() {
            const filtered = getFilteredExamResults();
            if (filtered.length === 0) {
                showToast('No records to print.', 'error');
                return;
            }

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Exam Results Report</title>
                    <style>
                        body { font-family: sans-serif; color: #1E293B; margin: 40px; }
                        h1 { color: #0077be; font-size: 24px; margin-bottom: 5px; }
                        h2 { font-size: 14px; color: #64748B; margin-top: 0; margin-bottom: 20px; font-weight: normal; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #E2E8F0; padding: 10px 12px; text-align: left; font-size: 12px; }
                        th { background-color: #F8FAFC; color: #475569; font-weight: bold; }
                        tr:nth-child(even) { background-color: #F8FAFC; }
                        .badge { background: #E2E8F0; color: #475569; padding: 3px 6px; border-radius: 4px; font-weight: bold; font-size: 11px; }
                        .footer { margin-top: 30px; font-size: 10px; color: #94A3B8; text-align: center; }
                    </style>
                </head>
                <body>
                    <h1>Smart School - Exam Results Report</h1>
                    <h2>Generated on: ${new Date().toLocaleString()}</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Exam Name</th>
                                <th>Term</th>
                                <th>Year</th>
                                <th>Marks</th>
                                <th>Grade</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${filtered.map(res => {
                                const studentName = res.student && res.student.user ? res.student.user.name : 'Unknown Student';
                                let studentGradeClass = '—';
                                if (res.student && res.student.grade) {
                                    const rawG = String(res.student.grade).trim();
                                    studentGradeClass = rawG.toLowerCase().startsWith('grade') ? rawG : `Grade ${rawG}`;
                                }
                                const subjectName = res.subject ? res.subject.subject_name : 'Unknown Subject';
                                return `
                                    <tr>
                                        <td style="font-weight: bold;">${studentName}</td>
                                        <td style="font-weight: bold; color: #0077be;">${studentGradeClass}</td>
                                        <td>${subjectName}</td>
                                        <td>${res.exam_name || '—'}</td>
                                        <td>${res.term}</td>
                                        <td>${res.academic_year}</td>
                                        <td>${res.marks_obtained} / ${res.total_marks}</td>
                                        <td><span class="badge">${res.grade}</span></td>
                                        <td>${res.remarks || '—'}</td>
                                    </tr>
                                `;
                            }).join('')}
                        </tbody>
                    </table>
                    <div class="footer">
                        Smart School System &bull; Confidential Academic Record
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                            setTimeout(function() { window.close(); }, 500);
                        };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        // --- NEW REPORT & EXPORT HELPERS ---
        function getFilteredUsers() {
            const search = (document.getElementById('users-search')?.value || '').toLowerCase();
            const filter = document.getElementById('users-filter')?.value || 'all';
            return (usersData || []).filter(user => {
                const matchesSearch = (user.name || '').toLowerCase().includes(search) || 
                                      (user.email || '').toLowerCase().includes(search) ||
                                      (user.phone && user.phone.includes(search));
                const matchesRole = filter === 'all' || (user.role || '').toLowerCase() === filter.toLowerCase();
                return matchesSearch && matchesRole;
            });
        }

        function exportUsersCSV() {
            const filtered = getFilteredUsers();
            if (filtered.length === 0) {
                showToast('No records to export.', 'error');
                return;
            }
            let csv = "data:text/csv;charset=utf-8,Name,Email,Phone,Address,Role\n";
            filtered.forEach(u => {
                const name = (u.name || '').replace(/"/g, '""');
                const email = (u.email || '').replace(/"/g, '""');
                const phone = (u.phone || '').replace(/"/g, '""');
                const address = (u.address || '').replace(/"/g, '""');
                const role = (u.role || '').replace(/"/g, '""');
                csv += `"${name}","${email}","${phone}","${address}","${role}"\n`;
            });
            const encodedUri = encodeURI(csv);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `users_directory_${new Date().toISOString().slice(0,10)}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function printUsersPDF() {
            const filtered = getFilteredUsers();
            if (filtered.length === 0) {
                showToast('No records to print.', 'error');
                return;
            }
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Users Directory Report</title>
                    <style>
                        body { font-family: sans-serif; color: #1E293B; margin: 40px; }
                        h1 { color: #0077be; font-size: 24px; margin-bottom: 5px; }
                        h2 { font-size: 14px; color: #64748B; margin-top: 0; margin-bottom: 20px; font-weight: normal; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #E2E8F0; padding: 10px 12px; text-align: left; font-size: 12px; }
                        th { background-color: #F8FAFC; color: #475569; font-weight: bold; }
                        tr:nth-child(even) { background-color: #F8FAFC; }
                        .badge { background: #E2E8F0; color: #475569; padding: 3px 6px; border-radius: 4px; font-weight: bold; font-size: 11px; }
                        .footer { margin-top: 30px; font-size: 10px; color: #94A3B8; text-align: center; }
                    </style>
                </head>
                <body>
                    <h1>Smart School - Users Directory Report</h1>
                    <h2>Generated on: ${new Date().toLocaleString()}</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${filtered.map(u => `
                                <tr>
                                    <td style="font-weight: bold;">${u.name || '—'}</td>
                                    <td>${u.email || '—'}</td>
                                    <td>${u.phone || '—'}</td>
                                    <td>${u.address || '—'}</td>
                                    <td><span class="badge">${u.role || '—'}</span></td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                    <div class="footer">
                        Smart School System &bull; Confidential Directory Report
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                            setTimeout(function() { window.close(); }, 500);
                        };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        function getFilteredTeachers() {
            const search = (document.getElementById('teachers-search')?.value || '').toLowerCase();
            return (teachersData || []).filter(user => {
                const teacherInfo = user.teacher || {};
                const matchesSearch = (user.name || '').toLowerCase().includes(search) ||
                                      (teacherInfo.employee_id && teacherInfo.employee_id.toLowerCase().includes(search)) ||
                                      (teacherInfo.subject_specialization && teacherInfo.subject_specialization.toLowerCase().includes(search));
                return matchesSearch;
            });
        }

        function exportTeachersCSV() {
            const filtered = getFilteredTeachers();
            if (filtered.length === 0) {
                showToast('No records to export.', 'error');
                return;
            }
            let csv = "data:text/csv;charset=utf-8,Employee ID,Name,Email,Qualification,Specialization,Monthly Salary\n";
            filtered.forEach(u => {
                const t = u.teacher || {};
                const empId = (t.employee_id || '').replace(/"/g, '""');
                const name = (u.name || '').replace(/"/g, '""');
                const email = (u.email || '').replace(/"/g, '""');
                const qual = (t.qualification || '').replace(/"/g, '""');
                const spec = (t.subject_specialization || '').replace(/"/g, '""');
                const sal = t.salary || 50000;
                csv += `"${empId}","${name}","${email}","${qual}","${spec}",${sal}\n`;
            });
            const encodedUri = encodeURI(csv);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `academic_staff_${new Date().toISOString().slice(0,10)}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function printTeachersPDF() {
            const filtered = getFilteredTeachers();
            if (filtered.length === 0) {
                showToast('No records to print.', 'error');
                return;
            }
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Academic Staff Report</title>
                    <style>
                        body { font-family: sans-serif; color: #1E293B; margin: 40px; }
                        h1 { color: #9c27b0; font-size: 24px; margin-bottom: 5px; }
                        h2 { font-size: 14px; color: #64748B; margin-top: 0; margin-bottom: 20px; font-weight: normal; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #E2E8F0; padding: 10px 12px; text-align: left; font-size: 12px; }
                        th { background-color: #F8FAFC; color: #475569; font-weight: bold; }
                        tr:nth-child(even) { background-color: #F8FAFC; }
                        .footer { margin-top: 30px; font-size: 10px; color: #94A3B8; text-align: center; }
                    </style>
                </head>
                <body>
                    <h1>Smart School - Academic Staff Report</h1>
                    <h2>Generated on: ${new Date().toLocaleString()}</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Qualification</th>
                                <th>Specialization</th>
                                <th>Monthly Salary</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${filtered.map(user => {
                                const t = user.teacher || {};
                                return `
                                    <tr>
                                        <td style="font-weight: bold; color: #9c27b0;">${t.employee_id || '—'}</td>
                                        <td style="font-weight: bold;">${user.name || '—'}</td>
                                        <td>${user.email || '—'}</td>
                                        <td>${t.qualification || '—'}</td>
                                        <td style="font-style: italic;">${t.subject_specialization || '—'}</td>
                                        <td>LKR ${parseFloat(t.salary || 50000).toLocaleString('en-US', {minimumFractionDigits:2})}</td>
                                    </tr>
                                `;
                            }).join('')}
                        </tbody>
                    </table>
                    <div class="footer">
                        Smart School System &bull; Confidential Staff Records
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                            setTimeout(function() { window.close(); }, 500);
                        };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        function getFilteredStudentAttendance() {
            const search = (document.getElementById('attendance-search')?.value || '').toLowerCase().trim();
            const filterEl = document.getElementById('attendance-grade-filter');
            const selectedGrade = filterEl ? (filterEl.value || '').trim() : '';

            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const role = ((adminObj && adminObj.role) || localStorage.getItem('user_role') || 'admin').toLowerCase();

            return (attendanceData || []).filter(rec => {
                const u = rec.user || {};
                const student = u.student || {};
                const studentGrade = String(student.grade || '').trim();

                if (role === 'teacher') {
                    const assignedGrades = window.currentTeacherClassGrades || [];
                    if (assignedGrades.length === 0) return false;
                    const isAssigned = assignedGrades.some(g => isExactGradeMatch(g, studentGrade));
                    if (!isAssigned) return false;
                    if (selectedGrade && !isExactGradeMatch(selectedGrade, studentGrade)) return false;
                } else if (selectedGrade) {
                    if (!isExactGradeMatch(selectedGrade, studentGrade)) return false;
                }

                const gradeText = studentGrade ? ('grade ' + studentGrade.toLowerCase()) : '';
                const matchesSearch = !search ||
                                      (u.name && u.name.toLowerCase().includes(search)) ||
                                      (student.student_id && student.student_id.toLowerCase().includes(search)) ||
                                      (rec.date && rec.date.includes(search)) ||
                                      (rec.status && rec.status.toLowerCase().includes(search)) ||
                                      gradeText.includes(search);
                return matchesSearch;
            });
        }

        function exportStudentAttendanceCSV() {
            const filtered = getFilteredStudentAttendance();
            if (filtered.length === 0) {
                showToast('No records to export.', 'error');
                return;
            }
            let csv = "data:text/csv;charset=utf-8,Student ID,Student Name,Role,Date,Clock In,Clock Out,Status\n";
            filtered.forEach(rec => {
                const u = rec.user || {};
                const student = u.student || {};
                const studentId = (student.student_id || '—').replace(/"/g, '""');
                const name = (u.name || 'Unknown').replace(/"/g, '""');
                const role = (u.role || 'student').replace(/"/g, '""');
                const date = (rec.date || '').replace(/"/g, '""');
                const inTime = (rec.in_time || '—').replace(/"/g, '""');
                const outTime = (rec.out_time || '—').replace(/"/g, '""');
                const status = (rec.status || '').replace(/"/g, '""');
                csv += `"${studentId}","${name}","${role}","${date}","${inTime}","${outTime}","${status}"\n`;
            });
            const encodedUri = encodeURI(csv);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `student_attendance_${new Date().toISOString().slice(0,10)}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function printStudentAttendancePDF() {
            const filtered = getFilteredStudentAttendance();
            if (filtered.length === 0) {
                showToast('No records to print.', 'error');
                return;
            }
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Student Attendance Report</title>
                    <style>
                        body { font-family: sans-serif; color: #1E293B; margin: 40px; }
                        h1 { color: #4caf50; font-size: 24px; margin-bottom: 5px; }
                        h2 { font-size: 14px; color: #64748B; margin-top: 0; margin-bottom: 20px; font-weight: normal; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #E2E8F0; padding: 10px 12px; text-align: left; font-size: 12px; }
                        th { background-color: #F8FAFC; color: #475569; font-weight: bold; }
                        tr:nth-child(even) { background-color: #F8FAFC; }
                        .badge { background: #E2E8F0; color: #475569; padding: 3px 6px; border-radius: 4px; font-weight: bold; font-size: 11px; }
                        .footer { margin-top: 30px; font-size: 10px; color: #94A3B8; text-align: center; }
                    </style>
                </head>
                <body>
                    <h1>Smart School - Student Attendance Report</h1>
                    <h2>Generated on: ${new Date().toLocaleString()}</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Role</th>
                                <th>Date</th>
                                <th>Clock In</th>
                                <th>Clock Out</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${filtered.map(rec => {
                                const u = rec.user || {};
                                const student = u.student || {};
                                const studentId = student.student_id || '—';
                                return `
                                    <tr>
                                        <td style="font-weight: bold;">${studentId}</td>
                                        <td style="font-weight: bold;">${u.name || 'Unknown'}</td>
                                        <td>${u.role || '—'}</td>
                                        <td>${rec.date || '—'}</td>
                                        <td style="color:#4caf50; font-weight: bold;">${rec.in_time || '—'}</td>
                                        <td style="color:#ff9800; font-weight: bold;">${rec.out_time || '—'}</td>
                                        <td><span class="badge">${rec.status || '—'}</span></td>
                                    </tr>
                                `;
                            }).join('')}
                        </tbody>
                    </table>
                    <div class="footer">
                        Smart School System &bull; Confidential Student Attendance Report
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                            setTimeout(function() { window.close(); }, 500);
                        };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        function getFilteredTeacherAttendance() {
            const search = (document.getElementById('teacher-attendance-search')?.value || '').toLowerCase();
            return (teacherAttendanceData || []).filter(rec => {
                const u = rec.user || {};
                const teacher = u.teacher || {};
                const matchesSearch = (u.name && u.name.toLowerCase().includes(search)) ||
                                      (teacher.employee_id && teacher.employee_id.toLowerCase().includes(search)) ||
                                      (u.email && u.email.toLowerCase().includes(search)) ||
                                      (rec.date && rec.date.includes(search)) ||
                                      (rec.status && rec.status.toLowerCase().includes(search));
                return matchesSearch;
            });
        }

        function exportTeacherAttendanceCSV() {
            const filtered = getFilteredTeacherAttendance();
            if (filtered.length === 0) {
                showToast('No records to export.', 'error');
                return;
            }
            let csv = "data:text/csv;charset=utf-8,Teacher ID,Teacher Name,Email,Date,Clock In,Clock Out,Status\n";
            filtered.forEach(rec => {
                const u = rec.user || {};
                const teacher = u.teacher || {};
                const empId = (teacher.employee_id || '—').replace(/"/g, '""');
                const name = (u.name || 'Unknown').replace(/"/g, '""');
                const email = (u.email || '—').replace(/"/g, '""');
                const date = (rec.date || '').replace(/"/g, '""');
                const inTime = (rec.in_time || '—').replace(/"/g, '""');
                const outTime = (rec.out_time || '—').replace(/"/g, '""');
                const status = (rec.status || '').replace(/"/g, '""');
                csv += `"${empId}","${name}","${email}","${date}","${inTime}","${outTime}","${status}"\n`;
            });
            const encodedUri = encodeURI(csv);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `teacher_attendance_${new Date().toISOString().slice(0,10)}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function printTeacherAttendancePDF() {
            const filtered = getFilteredTeacherAttendance();
            if (filtered.length === 0) {
                showToast('No records to print.', 'error');
                return;
            }
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Teacher Attendance Report</title>
                    <style>
                        body { font-family: sans-serif; color: #1E293B; margin: 40px; }
                        h1 { color: #ff9800; font-size: 24px; margin-bottom: 5px; }
                        h2 { font-size: 14px; color: #64748B; margin-top: 0; margin-bottom: 20px; font-weight: normal; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #E2E8F0; padding: 10px 12px; text-align: left; font-size: 12px; }
                        th { background-color: #F8FAFC; color: #475569; font-weight: bold; }
                        tr:nth-child(even) { background-color: #F8FAFC; }
                        .badge { background: #E2E8F0; color: #475569; padding: 3px 6px; border-radius: 4px; font-weight: bold; font-size: 11px; }
                        .footer { margin-top: 30px; font-size: 10px; color: #94A3B8; text-align: center; }
                    </style>
                </head>
                <body>
                    <h1>Smart School - Teacher Attendance Report</h1>
                    <h2>Generated on: ${new Date().toLocaleString()}</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Teacher ID</th>
                                <th>Teacher Name</th>
                                <th>Email</th>
                                <th>Date</th>
                                <th>Clock In</th>
                                <th>Clock Out</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${filtered.map(rec => {
                                const u = rec.user || {};
                                const teacher = u.teacher || {};
                                const empId = teacher.employee_id || '—';
                                return `
                                    <tr>
                                        <td style="font-weight: bold;">${empId}</td>
                                        <td style="font-weight: bold;">${u.name || 'Unknown'}</td>
                                        <td>${u.email || '—'}</td>
                                        <td>${rec.date || '—'}</td>
                                        <td style="color:#4caf50; font-weight: bold;">${rec.in_time || '—'}</td>
                                        <td style="color:#ff9800; font-weight: bold;">${rec.out_time || '—'}</td>
                                        <td><span class="badge">${rec.status || '—'}</span></td>
                                    </tr>
                                `;
                            }).join('')}
                        </tbody>
                    </table>
                    <div class="footer">
                        Smart School System &bull; Confidential Teacher Attendance Report
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                            setTimeout(function() { window.close(); }, 500);
                        };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        function getFilteredFees() {
            const search = (document.getElementById('fees-students-search')?.value || '').toLowerCase();
            return (feesMonthlyData || []).filter(s =>
                (s.name || '').toLowerCase().includes(search) ||
                (s.email || '').toLowerCase().includes(search)
            );
        }

        function exportFeesCSV() {
            const filtered = getFilteredFees();
            if (filtered.length === 0) {
                showToast('No records to export.', 'error');
                return;
            }
            let csv = "data:text/csv;charset=utf-8,Student Name,Email,Grade,Payment Status\n";
            filtered.forEach(s => {
                const name = (s.name || '').replace(/"/g, '""');
                const email = (s.email || '').replace(/"/g, '""');
                const grade = (s.grade || '—').replace(/"/g, '""');
                const status = (s.payment_status || 'unpaid').replace(/"/g, '""');
                csv += `"${name}","${email}","${grade}","${status}"\n`;
            });
            const encodedUri = encodeURI(csv);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `fees_report_${new Date().toISOString().slice(0,10)}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function printFeesPDF() {
            const filtered = getFilteredFees();
            if (filtered.length === 0) {
                showToast('No records to print.', 'error');
                return;
            }
            const monthLabel = document.getElementById('fees-month-label')?.textContent || '—';
            const dueLabel = document.getElementById('fees-due-label')?.textContent || '—';
            const amountLabel = document.getElementById('fees-amount-label')?.textContent || '—';
            const paidLabel = document.getElementById('fees-paid-count')?.textContent || '0';
            const unpaidLabel = document.getElementById('fees-unpaid-count')?.textContent || '0';

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Fees & Payments Report - ${monthLabel}</title>
                    <style>
                        body { font-family: sans-serif; color: #1E293B; margin: 40px; }
                        h1 { color: #0077be; font-size: 24px; margin-bottom: 5px; }
                        h2 { font-size: 14px; color: #64748B; margin-top: 0; margin-bottom: 20px; font-weight: normal; }
                        .summary-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px; margin-bottom: 30px; background: #F8FAFC; padding: 15px; border-radius: 8px; border: 1px solid #E2E8F0; }
                        .summary-item { font-size: 12px; }
                        .summary-item span { color: #64748B; display: block; margin-bottom: 4px; }
                        .summary-item strong { font-size: 14px; color: #1E293B; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #E2E8F0; padding: 10px 12px; text-align: left; font-size: 12px; }
                        th { background-color: #F8FAFC; color: #475569; font-weight: bold; }
                        tr:nth-child(even) { background-color: #F8FAFC; }
                        .badge { padding: 3px 6px; border-radius: 4px; font-weight: bold; font-size: 11px; }
                        .badge-paid { background: #DEF7EC; color: #03543F; }
                        .badge-unpaid { background: #FDE8E8; color: #9B1C1C; }
                        .footer { margin-top: 30px; font-size: 10px; color: #94A3B8; text-align: center; }
                    </style>
                </head>
                <body>
                    <h1>Smart School - Fees & Payments Report</h1>
                    <h2>Generated on: ${new Date().toLocaleString()}</h2>
                    
                    <div class="summary-grid">
                        <div class="summary-item">
                            <span>Month</span>
                            <strong>${monthLabel}</strong>
                        </div>
                        <div class="summary-item">
                            <span>Due Date</span>
                            <strong>${dueLabel}</strong>
                        </div>
                        <div class="summary-item">
                            <span>Standard Amount</span>
                            <strong>${amountLabel}</strong>
                        </div>
                        <div class="summary-item">
                            <span>Paid Students</span>
                            <strong style="color: #03543F;">${paidLabel}</strong>
                        </div>
                        <div class="summary-item">
                            <span>Unpaid Students</span>
                            <strong style="color: #9B1C1C;">${unpaidLabel}</strong>
                        </div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Grade</th>
                                <th>Payment Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${filtered.map(s => {
                                const isPaid = s.payment_status === 'paid';
                                return `
                                    <tr>
                                        <td style="font-weight: bold;">${s.name || '—'}</td>
                                        <td>${s.email || '—'}</td>
                                        <td>${s.grade || '—'}</td>
                                        <td>
                                            <span class="badge ${isPaid ? 'badge-paid' : 'badge-unpaid'}">
                                                ${isPaid ? 'Paid' : 'Unpaid'}
                                            </span>
                                        </td>
                                    </tr>
                                `;
                            }).join('')}
                        </tbody>
                    </table>
                    <div class="footer">
                        Smart School System &bull; Confidential Financial Records
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                            setTimeout(function() { window.close(); }, 500);
                        };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        function getFilteredSalaries() {
            const search = (document.getElementById('salaries-teachers-search')?.value || '').toLowerCase();
            return (salariesMonthlyData || []).filter(t =>
                (t.name || '').toLowerCase().includes(search) ||
                (t.specialization || '').toLowerCase().includes(search)
            );
        }

        function exportSalariesCSV() {
            const filtered = getFilteredSalaries();
            if (filtered.length === 0) {
                showToast('No records to export.', 'error');
                return;
            }
            let csv = "data:text/csv;charset=utf-8,Teacher Name,Employee ID,Specialization,Basic Salary,Payment Status\n";
            filtered.forEach(t => {
                const name = (t.name || '').replace(/"/g, '""');
                const empId = (t.employee_id || '—').replace(/"/g, '""');
                const spec = (t.specialization || '—').replace(/"/g, '""');
                const basic = t.basic_salary || 0;
                const status = (t.payment_status || 'unpaid').replace(/"/g, '""');
                csv += `"${name}","${empId}","${spec}",${basic},"${status}"\n`;
            });
            const encodedUri = encodeURI(csv);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `salaries_report_${new Date().toISOString().slice(0,10)}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function printSalariesPDF() {
            const filtered = getFilteredSalaries();
            if (filtered.length === 0) {
                showToast('No records to print.', 'error');
                return;
            }
            const monthLabel = document.getElementById('salaries-month-label')?.textContent || '—';
            const totalLabel = document.getElementById('salaries-total-label')?.textContent || '—';
            const paidLabel = document.getElementById('salaries-paid-amount')?.textContent || '—';
            const unpaidLabel = document.getElementById('salaries-unpaid-amount')?.textContent || '—';
            const countsLabel = document.getElementById('salaries-staff-counts')?.textContent || '—';

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Teacher Salaries Report - ${monthLabel}</title>
                    <style>
                        body { font-family: sans-serif; color: #1E293B; margin: 40px; }
                        h1 { color: #0077be; font-size: 24px; margin-bottom: 5px; }
                        h2 { font-size: 14px; color: #64748B; margin-top: 0; margin-bottom: 20px; font-weight: normal; }
                        .summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 30px; background: #F8FAFC; padding: 15px; border-radius: 8px; border: 1px solid #E2E8F0; }
                        .summary-item { font-size: 12px; }
                        .summary-item span { color: #64748B; display: block; margin-bottom: 4px; }
                        .summary-item strong { font-size: 14px; color: #1E293B; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #E2E8F0; padding: 10px 12px; text-align: left; font-size: 12px; }
                        th { background-color: #F8FAFC; color: #475569; font-weight: bold; }
                        tr:nth-child(even) { background-color: #F8FAFC; }
                        .badge { padding: 3px 6px; border-radius: 4px; font-weight: bold; font-size: 11px; }
                        .badge-paid { background: #DEF7EC; color: #03543F; }
                        .badge-unpaid { background: #FDE8E8; color: #9B1C1C; }
                        .footer { margin-top: 30px; font-size: 10px; color: #94A3B8; text-align: center; }
                    </style>
                </head>
                <body>
                    <h1>Smart School - Teacher Salaries Report</h1>
                    <h2>Generated on: ${new Date().toLocaleString()}</h2>
                    
                    <div class="summary-grid">
                        <div class="summary-item">
                            <span>Month</span>
                            <strong>${monthLabel}</strong>
                        </div>
                        <div class="summary-item">
                            <span>Total Payroll</span>
                            <strong>${totalLabel}</strong>
                        </div>
                        <div class="summary-item">
                            <span>Paid Amount</span>
                            <strong style="color: #03543F;">${paidLabel}</strong>
                        </div>
                        <div class="summary-item">
                            <span>Status Breakdown</span>
                            <strong>${countsLabel}</strong>
                        </div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Teacher Name</th>
                                <th>Employee ID</th>
                                <th>Specialization</th>
                                <th>Basic Salary</th>
                                <th>Payment Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${filtered.map(t => {
                                const isPaid = t.payment_status === 'paid';
                                return `
                                    <tr>
                                        <td style="font-weight: bold;">${t.name || '—'}</td>
                                        <td>${t.employee_id || '—'}</td>
                                        <td>${t.specialization || '—'}</td>
                                        <td>LKR ${parseFloat(t.basic_salary).toLocaleString('en-US', {minimumFractionDigits:2})}</td>
                                        <td>
                                            <span class="badge ${isPaid ? 'badge-paid' : 'badge-unpaid'}">
                                                ${isPaid ? 'Paid' : 'Unpaid'}
                                            </span>
                                        </td>
                                    </tr>
                                `;
                            }).join('')}
                        </tbody>
                    </table>
                    <div class="footer">
                        Smart School System &bull; Confidential Financial Records
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                            setTimeout(function() { window.close(); }, 500);
                        };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }
        // --- END OF NEW REPORT & EXPORT HELPERS ---

        function renderExamResults() {
            const tbody = document.getElementById('exam-results-table-body');
            tbody.innerHTML = '';

            const searchVal = document.getElementById('exam-results-search').value.toLowerCase();
            const classFilterVal = document.getElementById('exam-results-filter-class')?.value;
            const subjectFilterVal = document.getElementById('exam-results-filter-subject').value;
            const termFilterVal = document.getElementById('exam-results-filter-term').value;
            const yearFilterVal = document.getElementById('exam-results-filter-year').value;

            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const role = ((adminObj && adminObj.role) || localStorage.getItem('user_role') || 'teacher').toLowerCase();

            if (role === 'teacher') {
                const teacherGrades = (window.currentTeacherGrades && window.currentTeacherGrades.length > 0)
                    ? window.currentTeacherGrades
                    : (window.currentTeacherClassGrades || []);

                const hasAssignedGrade = (teacherGrades.length > 0) || (gradesData.length > 0);
                if (!hasAssignedGrade) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="10" style="text-align: center; color: #697386; padding: 50px 20px;">
                                <div style="font-size:36px; margin-bottom:10px;">📚</div>
                                <h3 style="margin:0 0 6px 0; color:#1a1f36; font-size:16px;">You do not have an assigned class or subjects</h3>
                                <p style="margin:0; font-size:13px; color:#697386;">Please contact the school administrator to assign a class or subjects to you.</p>
                            </td>
                        </tr>
                    `;
                    return;
                }
            }

            const filtered = examResultsData.filter(res => {
                if (role === 'teacher') {
                    if (!isExamResultAccessibleToTeacher(res)) {
                        return false;
                    }
                }

                const studentName = (res.student && res.student.user ? res.student.user.name : '').toLowerCase();
                const matchesSearch = studentName.includes(searchVal);
                const matchesClass = !classFilterVal || isExactGradeMatch(classFilterVal, res.student ? res.student.grade : null);
                const matchesSubject = !subjectFilterVal || 
                    String(res.subject_id) === String(subjectFilterVal) ||
                    (res.subject && (String(res.subject.id) === String(subjectFilterVal) || (res.subject.subject_name && res.subject.subject_name.toLowerCase() === String(subjectFilterVal).toLowerCase())));
                const matchesTerm = !termFilterVal || res.term === termFilterVal;
                const matchesYear = !yearFilterVal || String(res.academic_year) === String(yearFilterVal);

                return matchesSearch && matchesClass && matchesSubject && matchesTerm && matchesYear;
            });

            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="10" style="text-align: center; color: #697386; padding: 30px;">No exam results found.</td></tr>';
                return;
            }

            filtered.forEach(res => {
                const studentName = res.student && res.student.user ? res.student.user.name : 'Unknown Student';
                
                let studentGradeClass = '—';
                if (res.student && res.student.grade) {
                    const rawG = String(res.student.grade).trim();
                    studentGradeClass = rawG.toLowerCase().startsWith('grade') ? rawG : `Grade ${rawG}`;
                }

                const subjectName = res.subject ? res.subject.subject_name : 'Unknown Subject';
                const remarks = res.remarks || '—';

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="font-weight:600; color:#1a1f36;">${studentName}</td>
                    <td style="font-weight:600; color:#4CAF50;">${studentGradeClass}</td>
                    <td>${subjectName}</td>
                    <td>${res.exam_name}</td>
                    <td>${res.term}</td>
                    <td>${res.academic_year}</td>
                    <td>${res.marks_obtained} / ${res.total_marks}</td>
                    <td><span class="badge" style="background:#ff9800; color:white; font-size:12px; font-weight:bold;">${res.grade}</span></td>
                    <td>${remarks}</td>
                    <td>
                        <div style="display:flex; gap:6px;">
                            <button class="btn-toggle-fee pay" onclick="openExamResultModal(${res.id})" style="padding:4px 8px; font-size:11px; width:auto; border:none; background:#0077be; color:white; cursor:pointer;">Edit</button>
                            <button class="btn-toggle-fee unpay" onclick="deleteExamResult(${res.id})" style="padding:4px 8px; font-size:11px; width:auto; border:none; background:#ff4d4f; color:white; cursor:pointer;">Delete</button>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function openExamResultModal(id = null) {
            const modal = document.getElementById('exam-result-modal');
            const form = document.getElementById('exam-result-form');
            form.reset();
            
            if (typeof populateExamResultGradesDropdown === 'function') {
                populateExamResultGradesDropdown();
            }

            // Re-populate students list based on default (empty) grade selection
            if (typeof populateExamResultStudentsDropdown === 'function') {
                populateExamResultStudentsDropdown();
            }

            if (id && typeof id !== 'object') {
                document.getElementById('exam-result-modal-title').innerText = 'Edit Exam Result';
                const record = examResultsData.find(r => String(r.id) === String(id));
                if (record) {
                    document.getElementById('exam-result-id').value = record.id;
                    
                    // Set grade if possible to filter students
                    if (record.student && record.student.grade) {
                        let gradeStr = record.student.grade;
                        if (record.student.class) gradeStr += ' ' + record.student.class;
                        document.getElementById('exam-result-grade').value = gradeStr.trim();
                        if (typeof populateExamResultStudentsDropdown === 'function') {
                            populateExamResultStudentsDropdown();
                        }
                    }

                    document.getElementById('exam-result-student').value = record.student_id;
                    document.getElementById('exam-result-subject').value = record.subject_id;
                    document.getElementById('exam-result-name').value = record.exam_name;
                    document.getElementById('exam-result-marks').value = record.marks_obtained;
                    document.getElementById('exam-result-total-marks').value = record.total_marks;
                    document.getElementById('exam-result-term').value = record.term;
                    document.getElementById('exam-result-year').value = record.academic_year;
                    document.getElementById('exam-result-remarks').value = record.remarks || '';
                    
                    // Disable student and subject select in edit mode to preserve references
                    document.getElementById('exam-result-student').disabled = true;
                    document.getElementById('exam-result-subject').disabled = true;
                }
            } else {
                document.getElementById('exam-result-modal-title').innerText = 'Add Exam Result';
                document.getElementById('exam-result-id').value = '';
                document.getElementById('exam-result-student').disabled = false;
                document.getElementById('exam-result-subject').disabled = false;
            }

            modal.classList.remove('hidden');
        }

        function closeExamResultModal() {
            document.getElementById('exam-result-modal').classList.add('hidden');
        }

        async function submitExamResult(e) {
            e.preventDefault();
            const id = document.getElementById('exam-result-id').value;
            const student_id = document.getElementById('exam-result-student').value;
            const subject_id = document.getElementById('exam-result-subject').value;
            const exam_name = document.getElementById('exam-result-name').value.trim();
            const marks_obtained = parseInt(document.getElementById('exam-result-marks').value);
            const total_marks = parseInt(document.getElementById('exam-result-total-marks').value);
            const term = document.getElementById('exam-result-term').value;
            const academic_year = parseInt(document.getElementById('exam-result-year').value);
            const remarks = document.getElementById('exam-result-remarks').value.trim();

            const payload = {
                student_id,
                subject_id,
                exam_name,
                marks_obtained,
                total_marks,
                term,
                academic_year,
                remarks
            };

            try {
                let res;
                if (id) {
                    // Edit
                    res = await apiRequest(`/exam-results/${id}`, {
                        method: 'PUT',
                        body: JSON.stringify({ marks_obtained, total_marks, remarks })
                    });
                } else {
                    // Create
                    res = await apiRequest('/exam-results', {
                        method: 'POST',
                        body: JSON.stringify(payload)
                    });
                }

                if (res.success || res.message) {
                    showToast(id ? 'Exam result updated!' : 'Exam result created!', 'success');
                    closeExamResultModal();
                    fetchExamResults();
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        async function deleteExamResult(id) {
            if (!confirm('Are you sure you want to delete this exam result?')) return;

            try {
                const res = await apiRequest(`/exam-results/${id}`, {
                    method: 'DELETE'
                });

                if (res.message || res.success) {
                    showToast('Exam result deleted successfully!', 'success');
                    fetchExamResults();
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        async function fetchTeacherSalaries() {
            try {
                const res = await apiRequest('/teacher/salaries');
                if (res.success && res.data) {
                    renderTeacherSalaries(res.data);
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        function renderTeacherSalaries(payments) {
            const tbody = document.getElementById('teacher-salaries-table-body');
            tbody.innerHTML = '';

            const user = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const salary = user.teacher ? user.teacher.salary : 50000.00;
            document.getElementById('teacher-basic-salary').innerText = `LKR ${Number(salary).toLocaleString('en-US', { minimumFractionDigits: 2 })}`;

            if (payments.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" style="text-align: center; color: #697386; padding: 30px;">No salary payment history found.</td>
                    </tr>
                `;
                return;
            }

            const monthNames = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

            payments.forEach(p => {
                const ref = p.reference || '—';
                const period = `${monthNames[p.month]} ${p.year}`;
                const amount = `LKR ${Number(p.amount).toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
                const payDate = p.payment_date ? new Date(p.payment_date).toLocaleDateString() : '—';
                const method = (p.payment_method || 'bank_transfer').replace('_', ' ').toUpperCase();
                const notes = p.notes || '—';

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="font-weight:600; color:#1a1f36;">${ref}</td>
                    <td>${period}</td>
                    <td style="font-weight:600; color:#4CAF50;">${amount}</td>
                    <td>${payDate}</td>
                    <td>${method}</td>
                    <td>${notes}</td>
                    <td><span class="badge status-present" style="background:#4CAF50; color:white; font-size:12px;">PAID</span></td>
                `;
                tbody.appendChild(tr);
            });
        }

        async function setupTeacherDashboard(user) {
            const valUsersEl = document.getElementById('val-users');
            const valTeachersEl = document.getElementById('val-teachers');
            const valLogsEl = document.getElementById('val-logs');

            if (valUsersEl) valUsersEl.innerText = 'Loading...';
            if (valTeachersEl) valTeachersEl.innerText = 'Loading...';
            if (valLogsEl) valLogsEl.innerText = 'Loading...';

            try {
                const adminObj = (user && user.id) ? user : JSON.parse(localStorage.getItem('admin_user') || '{}');
                const role = ((adminObj && adminObj.role) || localStorage.getItem('user_role') || 'teacher').toLowerCase();
                const userId = adminObj ? adminObj.id : null;
                const teacherId = adminObj && adminObj.teacher ? adminObj.teacher.id : null;

                const subjectsRes = await apiRequest('/subjects');
                const subjectsData = (subjectsRes && subjectsRes.success && Array.isArray(subjectsRes.data)) ? subjectsRes.data : [];
                window.allSubjectsData = subjectsData;
                if (valUsersEl) valUsersEl.innerText = subjectsData.length;

                const isMyTimetable = (t) => {
                    if (!t || !t.grade) return false;
                    return userId && t.grade.toLowerCase().includes(`teacher:${userId}`);
                };

                const subsRes = await apiRequest(`/subject-submissions?teacher_id=${teacherId || ''}&user_id=${userId || ''}`);
                const subsCount = (subsRes && subsRes.success && Array.isArray(subsRes.data)) ? subsRes.data.length : 0;
                if (valTeachersEl) valTeachersEl.innerText = subsCount;

                const timetablesRes = await apiRequest('/simple-timetables');
                const timetablesData = (timetablesRes && timetablesRes.success && Array.isArray(timetablesRes.data)) ? timetablesRes.data : [];
                
                const isTeacherTimetable = (t) => {
                    if (!t || !t.grade) return false;
                    return userId && t.grade.toLowerCase().includes(`teacher:${userId}`);
                };

                let teacherSlotsCount = 0;
                timetablesData.forEach(t => {
                    if (isTeacherTimetable(t)) {
                        for (const slot of Object.keys(slotsMetadata)) {
                            if (t[slot] && t[slot].trim()) {
                                teacherSlotsCount++;
                            }
                        }
                    }
                });
                if (valLogsEl) valLogsEl.innerText = teacherSlotsCount;

                // Load all grades
                const gradesRes = await apiRequest('/admin/grades');
                const gradesData = (gradesRes && gradesRes.success && Array.isArray(gradesRes.data)) ? gradesRes.data : [];

                const timetableGradeSet = new Set();
                const allTeacherGradeSet = new Set();

                const timetableSubjectSet = new Set();
                const allTeacherSubjectSet = new Set();

                const gradeToSubjectsMap = {};

                // 1. Timetable assignments (extract subject & grade from slots like "Science (Grade 1)")
                timetablesData.forEach(t => {
                    if (isTeacherTimetable(t)) {
                        for (const slot of Object.keys(slotsMetadata)) {
                            const val = t[slot];
                            if (val && typeof val === 'string' && val.trim()) {
                                const rawVal = val.trim();
                                
                                // Extract Grade inside parentheses e.g. "Science (Grade 1)"
                                const gradeMatch = rawVal.match(/\((Grade\s*[^)]+)\)/i) || rawVal.match(/\(([^)]+)\)/);
                                const slotGrade = gradeMatch && gradeMatch[1] ? gradeMatch[1].trim() : null;

                                // Extract Subject name (everything before parenthesis or dash)
                                const subName = rawVal.replace(/\s*\([^)]+\)/, '').split('-')[0].trim();
                                const ignoredNames = ['break', 'interval', 'lunch', 'free'];
                                if (subName && !ignoredNames.includes(subName.toLowerCase())) {
                                    timetableSubjectSet.add(subName);
                                    allTeacherSubjectSet.add(subName);

                                    if (slotGrade) {
                                        timetableGradeSet.add(slotGrade);
                                        allTeacherGradeSet.add(slotGrade);
                                        if (!gradeToSubjectsMap[slotGrade]) gradeToSubjectsMap[slotGrade] = new Set();
                                        gradeToSubjectsMap[slotGrade].add(subName);
                                    }
                                }
                            }
                        }
                    }
                });

                // 2. Class teacher assignments
                const classTeacherGradeSet = new Set();
                gradesData.forEach(g => {
                    if (isClassTeacherOfGrade(g, adminObj) && g.name) {
                        classTeacherGradeSet.add(g.name.trim());
                        allTeacherGradeSet.add(g.name.trim());
                    }
                });

                // 3. Subjects table assignments
                subjectsData.forEach(s => {
                    if ((teacherId && s.teacher_id === teacherId) || (userId && s.teacher_id === userId)) {
                        if (s.grade && s.subject_name) {
                            const gName = s.grade.trim();
                            const subName = s.subject_name.trim();
                            allTeacherGradeSet.add(gName);
                            allTeacherSubjectSet.add(subName);
                            if (!gradeToSubjectsMap[gName]) gradeToSubjectsMap[gName] = new Set();
                            gradeToSubjectsMap[gName].add(subName);
                        }
                    }
                });

                // 4. Specialization from profile (if specific)
                if (adminObj.teacher && adminObj.teacher.subject_specialization) {
                    const spec = adminObj.teacher.subject_specialization.trim();
                    if (spec && spec.toLowerCase() !== 'primary') {
                        spec.split(',').forEach(sub => {
                            const trimmed = sub.trim();
                            if (trimmed) allTeacherSubjectSet.add(trimmed);
                        });
                    }
                }

                // Filter grades for teacher
                window.currentTeacherClassGrades = Array.from(classTeacherGradeSet);
                window.currentTeacherGradeSubjectsMap = gradeToSubjectsMap;

                let teacherGrades = [];
                if (role === 'teacher') {
                    if (allTeacherGradeSet.size > 0) {
                        teacherGrades = Array.from(allTeacherGradeSet).sort();
                    } else if (classTeacherGradeSet.size > 0) {
                        teacherGrades = Array.from(classTeacherGradeSet).sort();
                    } else if (timetableGradeSet.size > 0) {
                        teacherGrades = Array.from(timetableGradeSet).sort();
                    } else {
                        teacherGrades = [];
                    }
                } else {
                    teacherGrades = gradesData.map(g => g.name.trim());
                }

                window.currentTeacherGrades = teacherGrades;

                const materialGradeSelect = document.getElementById('material-grade');
                const assignmentGradeSelect = document.getElementById('assignment-grade');
                
                const populateGradeSelect = (selectEl) => {
                    if (!selectEl) return;
                    selectEl.innerHTML = '';
                    if (teacherGrades.length === 0) {
                        const opt = document.createElement('option');
                        opt.value = '';
                        opt.textContent = 'No assigned grades found';
                        selectEl.appendChild(opt);
                    } else {
                        teacherGrades.forEach(gName => {
                            const opt = document.createElement('option');
                            opt.value = gName;
                            opt.textContent = gName;
                            selectEl.appendChild(opt);
                        });
                    }
                };

                populateGradeSelect(materialGradeSelect);
                populateGradeSelect(assignmentGradeSelect);

                // Filter subjects for teacher
                let teacherSubjects = [];
                if (role === 'teacher') {
                    if (timetableSubjectSet.size > 0) {
                        teacherSubjects = Array.from(timetableSubjectSet).sort();
                    } else if (allTeacherSubjectSet.size > 0) {
                        teacherSubjects = Array.from(allTeacherSubjectSet).sort();
                    } else {
                        const defaultSubjects = ['Mathematics', 'Science', 'English', 'Sinhala', 'Tamil', 'History', 'Geography', 'ICT', 'Commerce', 'Health & Physical Education', 'Art', 'Music', 'Dancing', 'Civic Education', 'Religion'];
                        teacherSubjects = defaultSubjects;
                    }
                } else {
                    const defaultSubjects = ['Mathematics', 'Science', 'English', 'Sinhala', 'Tamil', 'History', 'Geography', 'ICT', 'Commerce', 'Health & Physical Education', 'Art', 'Music', 'Dancing', 'Civic Education', 'Religion'];
                    let allSubjects = [...defaultSubjects];
                    subjectsData.forEach(s => {
                        if (s.subject_name && !allSubjects.includes(s.subject_name)) {
                            allSubjects.push(s.subject_name);
                        }
                    });
                    teacherSubjects = allSubjects.sort();
                }

                window.currentTeacherSubjects = teacherSubjects;

                // Function to update subjects dropdown depending on selected grade
                const updateSubjectsForSelectedGrade = (gradeSelectId, subjectSelectId) => {
                    const gradeSelect = document.getElementById(gradeSelectId);
                    const subjectSelect = document.getElementById(subjectSelectId);
                    if (!gradeSelect || !subjectSelect) return;

                    const selGrade = (gradeSelect.value || '').trim();
                    let subjectsForThisGrade = [];

                    if (role === 'teacher') {
                        if (selGrade && gradeToSubjectsMap[selGrade] && gradeToSubjectsMap[selGrade].size > 0) {
                            subjectsForThisGrade = Array.from(gradeToSubjectsMap[selGrade]).sort();
                        } else if (selGrade) {
                            const matchedK = Object.keys(gradeToSubjectsMap).find(k => k.toLowerCase() === selGrade.toLowerCase());
                            if (matchedK && gradeToSubjectsMap[matchedK].size > 0) {
                                subjectsForThisGrade = Array.from(gradeToSubjectsMap[matchedK]).sort();
                            }
                        }

                        if (subjectsForThisGrade.length === 0) {
                            subjectsForThisGrade = teacherSubjects;
                        }
                    } else {
                        subjectsForThisGrade = teacherSubjects;
                    }

                    subjectSelect.innerHTML = '';
                    if (subjectsForThisGrade.length === 0) {
                        const opt = document.createElement('option');
                        opt.value = '';
                        opt.textContent = 'No subjects assigned for this grade';
                        subjectSelect.appendChild(opt);
                    } else {
                        subjectsForThisGrade.forEach(sub => {
                            const opt = document.createElement('option');
                            opt.value = sub;
                            opt.textContent = sub;
                            subjectSelect.appendChild(opt);
                        });
                    }
                };

                const materialSubjectSelect = document.getElementById('material-subject-select');
                const assignmentSubjectSelect = document.getElementById('assignment-subject-select');

                if (materialGradeSelect) {
                    materialGradeSelect.onchange = () => updateSubjectsForSelectedGrade('material-grade', 'material-subject-select');
                }
                if (assignmentGradeSelect) {
                    assignmentGradeSelect.onchange = () => updateSubjectsForSelectedGrade('assignment-grade', 'assignment-subject-select');
                }

                // Initial populate based on currently selected grade
                updateSubjectsForSelectedGrade('material-grade', 'material-subject-select');
                updateSubjectsForSelectedGrade('assignment-grade', 'assignment-subject-select');

            } catch (err) {
                console.error("Dashboard error: ", err);
                if (valUsersEl) valUsersEl.innerText = '—';
                if (valTeachersEl) valTeachersEl.innerText = '—';
                if (valLogsEl) valLogsEl.innerText = '—';
            }
        }

        // QR Code Handlers
        let currentQrData = '';
        let currentQrName = '';

        function showUserQrCode(userId, userName, userRole, studentId) {
            currentQrName = userName.replace(/\s+/g, '_') + '_QR.png';
            const qrData = userRole.toLowerCase() === 'student' ? `student:${studentId}` : `teacher:${userId}`;
            currentQrData = qrData;
            
            const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(qrData)}`;
            document.getElementById('qr-image').src = qrUrl;
            document.getElementById('qr-user-name').innerText = userName;
            document.getElementById('qr-user-role').innerText = userRole;
            
            document.getElementById('qr-modal').classList.remove('hidden');
        }

        function closeQrModal() {
            document.getElementById('qr-modal').classList.add('hidden');
        }

        async function downloadQrCode() {
            if (!currentQrData) return;
            const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(currentQrData)}`;
            await triggerImageDownload(qrUrl, currentQrName);
        }

        async function downloadTeacherQrCode() {
            const admin = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const qrData = `teacher:${admin.id}`;
            const fileName = (admin.name || 'Teacher').replace(/\s+/g, '_') + '_QR.png';
            const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(qrData)}`;
            await triggerImageDownload(qrUrl, fileName);
        }

        async function triggerImageDownload(url, filename) {
            try {
                const response = await fetch(url);
                const blob = await response.blob();
                const blobUrl = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = blobUrl;
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(blobUrl);
            } catch (err) {
                showToast('Failed to download QR code: ' + err.message, 'error');
            }
        }

        // User management CRUD Handlers
        function openCreateUserModal(role) {
            const form = document.getElementById('user-form');
            form.reset();
            
            document.getElementById('user-id').value = '';
            document.getElementById('user-role-val').value = role;
            document.getElementById('user-modal-title').innerText = `Register New ${role.charAt(0).toUpperCase() + role.slice(1)}`;
            
            // Password is required for new registration
            document.getElementById('user-password-container').classList.remove('hidden');
            document.getElementById('user-password').setAttribute('required', 'required');

            // Toggle role specific sections
            const studentSection = document.getElementById('student-fields-section');
            const teacherSection = document.getElementById('teacher-fields-section');
            
            if (role === 'student') {
                studentSection.classList.remove('hidden');
                teacherSection.classList.add('hidden');
            } else if (role === 'teacher') {
                studentSection.classList.add('hidden');
                teacherSection.classList.remove('hidden');
            } else {
                studentSection.classList.add('hidden');
                teacherSection.classList.add('hidden');
            }
            
            document.getElementById('user-modal').classList.remove('hidden');
        }

        function openEditUserModal(id) {
            const form = document.getElementById('user-form');
            form.reset();

            const user = usersData.find(u => String(u.id) === String(id));
            if (!user) {
                showToast('User data not found.', 'error');
                return;
            }

            document.getElementById('user-id').value = user.id;
            document.getElementById('user-role-val').value = user.role;
            document.getElementById('user-modal-title').innerText = `Edit ${user.role.charAt(0).toUpperCase() + user.role.slice(1)}: ${user.name}`;

            // Populate common fields
            document.getElementById('user-name').value = user.name || '';
            document.getElementById('user-email').value = user.email || '';
            document.getElementById('user-phone').value = user.phone || '';
            document.getElementById('user-dob').value = user.dob || '';
            document.getElementById('user-address').value = user.address || '';

            // Password container hidden in edit mode
            document.getElementById('user-password-container').classList.add('hidden');
            document.getElementById('user-password').removeAttribute('required');

            // Role specific fields
            const studentSection = document.getElementById('student-fields-section');
            const teacherSection = document.getElementById('teacher-fields-section');

            if (user.role.toLowerCase() === 'student') {
                studentSection.classList.remove('hidden');
                teacherSection.classList.add('hidden');

                if (user.student) {
                    document.getElementById('student-grade').value = user.student.grade || '1';
                    if (document.getElementById('student-class')) document.getElementById('student-class').value = user.student.class || 'A';
                    document.getElementById('student-parent-name').value = user.student.parent_name || (user.student.parent ? user.student.parent.name : '') || '';
                    document.getElementById('student-parent-email').value = user.student.parent_email || (user.student.parent ? user.student.parent.email : '') || '';
                    document.getElementById('student-parent-phone').value = user.student.parent_phone || (user.student.parent ? user.student.parent.phone : '') || '';
                }
            } else if (user.role.toLowerCase() === 'teacher') {
                studentSection.classList.add('hidden');
                teacherSection.classList.remove('hidden');

                if (user.teacher) {
                    document.getElementById('teacher-subject').value = user.teacher.subject_specialization || '';
                    document.getElementById('teacher-qualification').value = user.teacher.qualification || '';
                }
            } else {
                studentSection.classList.add('hidden');
                teacherSection.classList.add('hidden');
            }

            document.getElementById('user-modal').classList.remove('hidden');
        }

        function closeUserModal() {
            document.getElementById('user-modal').classList.add('hidden');
        }

        async function submitUserForm(e) {
            e.preventDefault();
            
            const id = document.getElementById('user-id').value;
            const role = document.getElementById('user-role-val').value;
            
            const payload = {
                name: document.getElementById('user-name').value.trim(),
                email: document.getElementById('user-email').value.trim(),
                phone: document.getElementById('user-phone').value.trim(),
                dob: document.getElementById('user-dob').value,
                address: document.getElementById('user-address').value.trim(),
                role: role
            };

            if (!id) {
                payload.password = document.getElementById('user-password').value;
            }

            if (role.toLowerCase() === 'student') {
                payload.grade = document.getElementById('student-grade').value;
                if (document.getElementById('student-class')) {
                    payload.class = document.getElementById('student-class').value;
                }
                payload.parent_name = document.getElementById('student-parent-name').value.trim();
                payload.parent_email = document.getElementById('student-parent-email').value.trim();
                payload.parent_phone = document.getElementById('student-parent-phone').value.trim();
            } else if (role.toLowerCase() === 'teacher') {
                payload.subject_specialization = document.getElementById('teacher-subject').value.trim();
                payload.qualification = document.getElementById('teacher-qualification').value.trim();
            }

            try {
                let res;
                if (id) {
                    res = await apiRequest(`/admin/users/${id}`, {
                        method: 'PUT',
                        body: JSON.stringify(payload)
                    });
                } else {
                    res = await apiRequest('/admin/users', {
                        method: 'POST',
                        body: JSON.stringify(payload)
                    });
                }

                if (res.success) {
                    showToast(id ? 'User updated successfully!' : 'User registered successfully!', 'success');
                    closeUserModal();
                    await fetchUsers();
                    if (typeof fetchDashboardMetrics === 'function') {
                        await fetchDashboardMetrics();
                    }
                } else {
                    showToast(res.message || 'Error occurred while saving.', 'error');
                }
            } catch (err) {
                showToast(err.message || 'Action failed.', 'error');
            }
        }

        async function confirmDeleteUser(id, name) {
            if (!confirm(`Are you sure you want to delete user "${name}"? This action cannot be undone.`)) {
                return;
            }

            try {
                const res = await apiRequest(`/admin/users/${id}`, {
                    method: 'DELETE'
                });

                if (res.success) {
                    showToast('User deleted successfully!', 'success');
                    await fetchUsers();
                    if (typeof fetchDashboardMetrics === 'function') {
                        await fetchDashboardMetrics();
                    }
                } else {
                    showToast(res.message || 'Failed to delete user.', 'error');
                }
            } catch (err) {
                showToast(err.message || 'Action failed.', 'error');
            }
        }

        function openChangePasswordModal(id, name) {
            document.getElementById('change-password-form').reset();
            document.getElementById('change-password-user-id').value = id;
            document.getElementById('change-password-user-name').innerText = name;
            document.getElementById('change-password-modal').classList.remove('hidden');
        }

        function closeChangePasswordModal() {
            document.getElementById('change-password-modal').classList.add('hidden');
        }

        async function submitChangePassword(e) {
            e.preventDefault();

            const id = document.getElementById('change-password-user-id').value;
            const new_password = document.getElementById('new-password').value;

            try {
                const res = await apiRequest(`/admin/users/${id}/password`, {
                    method: 'PUT',
                    body: JSON.stringify({ new_password })
                });

                if (res.success) {
                    showToast('Password changed successfully!', 'success');
                    closeChangePasswordModal();
                } else {
                    showToast(res.message || 'Failed to change password.', 'error');
                }
            } catch (err) {
                showToast(err.message || 'Action failed.', 'error');
            }
        }

        // Subject Edit / Delete Handlers
        function openEditSubjectModal(id, name, grade) {
            document.getElementById('edit-subject-form').reset();
            document.getElementById('edit-subject-id').value = id;
            document.getElementById('edit-subject-name').value = name;
            // Normalize: strip section suffix like "Grade 1 A" → "Grade 1"
            const normalizedGrade = grade.replace(/\s+[A-Z]$/i, '').trim();
            document.getElementById('edit-subject-grade').value = normalizedGrade;
            document.getElementById('edit-subject-modal').classList.remove('hidden');
        }

        function closeEditSubjectModal() {
            document.getElementById('edit-subject-modal').classList.add('hidden');
        }

        async function submitEditSubject(e) {
            e.preventDefault();
            const id = document.getElementById('edit-subject-id').value;
            const subject_name = document.getElementById('edit-subject-name').value.trim();
            const grade = document.getElementById('edit-subject-grade').value.trim();

            try {
                const res = await apiRequest(`/subjects/${id}`, {
                    method: 'PUT',
                    body: JSON.stringify({ subject_name, grade })
                });

                if (res.success) {
                    showToast('Subject updated successfully!', 'success');
                    closeEditSubjectModal();
                    await fetchSubjectsManagement();
                } else {
                    showToast(res.message || 'Failed to update subject.', 'error');
                }
            } catch (err) {
                showToast(err.message || 'Action failed.', 'error');
            }
        }

        async function confirmDeleteSubject(id, name) {
            if (!confirm(`Are you sure you want to delete subject "${name}"? This action cannot be undone.`)) {
                return;
            }

            try {
                const res = await apiRequest(`/subjects/${id}`, {
                    method: 'DELETE'
                });

                if (res.success) {
                    showToast('Subject deleted successfully!', 'success');
                    await fetchSubjectsManagement();
                } else {
                    showToast(res.message || 'Failed to delete subject.', 'error');
                }
            } catch (err) {
                showToast(err.message || 'Action failed.', 'error');
            }
        }

        // ─── EVENT GALLERY FUNCTIONS (ADMIN ONLY) ───────────────────────────
        let galleryData = [];

        async function fetchGalleries() {
            const container = document.getElementById('gallery-events-container');
            if (container) {
                container.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; color: #697386; padding: 50px;" class="glass-card">Loading gallery albums...</div>';
            }
            try {
                const res = await apiRequest('/galleries');
                if (res.success && Array.isArray(res.data)) {
                    galleryData = res.data;
                    renderGalleryGrid();
                }
            } catch (err) {
                if (container) {
                    container.innerHTML = `<div style="grid-column: 1 / -1; text-align: center; color: #ff4d4f; padding: 40px;" class="glass-card">Failed to load galleries: ${err.message}</div>`;
                }
            }
        }

        function renderGalleryGrid() {
            const container = document.getElementById('gallery-events-container');
            if (!container) return;

            const search = (document.getElementById('gallery-search')?.value || '').toLowerCase();
            const filtered = (galleryData || []).filter(g => {
                return (g.event_name || '').toLowerCase().includes(search) ||
                       (g.description || '').toLowerCase().includes(search);
            });

            if (filtered.length === 0) {
                container.innerHTML = `
                    <div style="grid-column: 1 / -1; text-align: center; color: #697386; padding: 60px 20px;" class="glass-card">
                        <div style="font-size:40px; margin-bottom:10px;">🖼️</div>
                        <h3 style="margin:0 0 6px 0; color:#1a1f36; font-size:16px;">No Gallery Events Found</h3>
                        <p style="margin:0; font-size:13px; color:#697386;">Click "+ Add Gallery Event" to create a new photo album for school events.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = '';
            filtered.forEach(album => {
                const images = album.images || [];
                const imgCount = images.length;
                const dateStr = album.event_date ? new Date(album.event_date).toLocaleDateString() : '';

                let previewHtml = '';
                if (imgCount > 0) {
                    const previewImgs = images.slice(0, 4);
                    previewHtml = `
                        <div style="display: grid; grid-template-columns: repeat(${previewImgs.length === 1 ? 1 : 2}, 1fr); gap: 6px; border-radius: 12px; overflow: hidden; height: 180px; margin-bottom: 14px; background: #f0f4f8; border: 1px solid rgba(0,0,0,0.05);">
                            ${previewImgs.map(img => `
                                <div style="position:relative; width:100%; height:100%; overflow:hidden;">
                                    <img src="${img.image_url}" style="width:100%; height:100%; object-fit:cover; cursor:pointer;" onclick="openGalleryLightbox(${album.id})">
                                </div>
                            `).join('')}
                        </div>
                    `;
                } else {
                    previewHtml = `
                        <div style="height: 140px; background: #f0f4f8; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 13px; margin-bottom: 14px;">
                            No Photos Uploaded
                        </div>
                    `;
                }

                const card = document.createElement('div');
                card.className = 'glass-card';
                card.style.cssText = 'padding: 20px; border-radius: 16px; display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.2s;';
                card.innerHTML = `
                    <div>
                        ${previewHtml}
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:6px;">
                            <h3 style="margin:0; font-size:16px; font-weight:700; color:#1a1f36;">${album.event_name}</h3>
                            <span class="badge" style="background:rgba(0,119,190,0.1); color:#0077be; font-weight:700; font-size:11px; padding:4px 8px; border-radius:6px; white-space:nowrap;">
                                🖼️ ${imgCount} Photo${imgCount === 1 ? '' : 's'}
                            </span>
                        </div>
                        ${dateStr ? `<div style="font-size:12px; color:#697386; margin-bottom:8px;">📅 ${dateStr}</div>` : ''}
                        ${album.description ? `<p style="font-size:13px; color:#475569; margin:0 0 14px 0; line-height:1.4;">${album.description}</p>` : ''}
                    </div>

                    <div style="display:flex; gap:8px; margin-top:14px; border-top:1px solid rgba(0,0,0,0.06); padding-top:12px;">
                        <button onclick="openGalleryLightbox(${album.id})" class="btn" style="flex:1; background:#0077be; color:white; border:none; padding:8px 12px; font-size:12px; font-weight:700; border-radius:8px; cursor:pointer;">
                            🔍 View All (${imgCount})
                        </button>
                        <button onclick="openAddMoreImagesModal(${album.id})" class="btn" style="background:#4CAF50; color:white; border:none; padding:8px 12px; font-size:12px; font-weight:700; border-radius:8px; cursor:pointer;" title="Add Photos">
                            + Add Photos
                        </button>
                        <button onclick="deleteGalleryAlbum(${album.id})" class="btn" style="background:#ff4d4f; color:white; border:none; padding:8px 12px; font-size:12px; font-weight:700; border-radius:8px; cursor:pointer;" title="Delete Album">
                            🗑️
                        </button>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function openAddGalleryModal() {
            document.getElementById('gallery-form').reset();
            document.getElementById('gallery-event-date').value = new Date().toISOString().slice(0, 10);
            document.getElementById('gallery-images-preview-container').innerHTML = '';
            document.getElementById('gallery-modal').classList.remove('hidden');
        }

        function closeGalleryModal() {
            document.getElementById('gallery-modal').classList.add('hidden');
        }

        function handleGalleryImagesPreview(e) {
            const container = document.getElementById('gallery-images-preview-container');
            container.innerHTML = '';
            const files = e.target.files;
            if (!files || files.length === 0) return;

            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = (evt) => {
                    const imgDiv = document.createElement('div');
                    imgDiv.style.cssText = 'position:relative; width:60px; height:60px; border-radius:8px; overflow:hidden; border:1px solid rgba(0,0,0,0.1);';
                    imgDiv.innerHTML = `<img src="${evt.target.result}" style="width:100%; height:100%; object-fit:cover;">`;
                    container.appendChild(imgDiv);
                };
                reader.readAsDataURL(file);
            });
        }

        async function handleCreateGallery(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('gallery-submit-btn');
            const event_name = document.getElementById('gallery-event-name').value.trim();
            const event_date = document.getElementById('gallery-event-date').value;
            const description = document.getElementById('gallery-description').value.trim();
            const fileInput = document.getElementById('gallery-images-input');

            if (!fileInput.files || fileInput.files.length === 0) {
                showToast('Please select at least one photo for the event.', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('event_name', event_name);
            formData.append('event_date', event_date);
            formData.append('description', description);

            Array.from(fileInput.files).forEach(file => {
                formData.append('images[]', file);
            });

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Uploading photos...</span>';

            try {
                const token = localStorage.getItem('admin_token') || localStorage.getItem('auth_token') || localStorage.getItem('token') || '';
                const response = await fetch('/api/galleries', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': token ? `Bearer ${token}` : ''
                    },
                    body: formData
                });

                const res = await response.json();

                if (response.ok && (res.success || res.data)) {
                    showToast('Gallery event album created successfully!', 'success');
                    closeGalleryModal();
                    fetchGalleries();
                } else {
                    showToast(res.message || 'Failed to create gallery album.', 'error');
                }
            } catch (err) {
                showToast(err.message || 'Upload failed.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>Upload Gallery Album</span>';
            }
        }

        function openAddMoreImagesModal(galleryId) {
            document.getElementById('add-more-images-form').reset();
            document.getElementById('add-more-images-gallery-id').value = galleryId;
            document.getElementById('add-more-images-modal').classList.remove('hidden');
        }

        function closeAddMoreImagesModal() {
            document.getElementById('add-more-images-modal').classList.add('hidden');
        }

        async function handleUploadMoreImages(e) {
            e.preventDefault();
            const galleryId = document.getElementById('add-more-images-gallery-id').value;
            const fileInput = document.getElementById('add-more-images-input');
            const submitBtn = document.getElementById('add-more-submit-btn');

            if (!fileInput.files || fileInput.files.length === 0) {
                showToast('Please select at least one photo to upload.', 'error');
                return;
            }

            const formData = new FormData();
            Array.from(fileInput.files).forEach(file => {
                formData.append('images[]', file);
            });

            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Uploading...';

            try {
                const token = localStorage.getItem('admin_token') || localStorage.getItem('auth_token') || localStorage.getItem('token') || '';
                const response = await fetch(`/api/galleries/${galleryId}/add-images`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': token ? `Bearer ${token}` : ''
                    },
                    body: formData
                });

                const res = await response.json();

                if (response.ok && (res.success || res.data)) {
                    showToast('New photos added to album successfully!', 'success');
                    closeAddMoreImagesModal();
                    fetchGalleries();
                } else {
                    showToast(res.message || 'Failed to upload photos.', 'error');
                }
            } catch (err) {
                showToast(err.message || 'Action failed.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Upload Photos';
            }
        }

        async function deleteGalleryAlbum(id) {
            if (!confirm('Are you sure you want to delete this gallery album and all its photos?')) return;

            try {
                const res = await apiRequest(`/galleries/${id}`, {
                    method: 'DELETE'
                });

                if (res.success || res.message) {
                    showToast('Gallery album deleted successfully!', 'success');
                    fetchGalleries();
                } else {
                    showToast(res.message || 'Failed to delete album.', 'error');
                }
            } catch (err) {
                showToast(err.message || 'Action failed.', 'error');
            }
        }

        function openGalleryLightbox(albumId) {
            const album = (galleryData || []).find(g => String(g.id) === String(albumId));
            if (!album) return;

            document.getElementById('lightbox-title').innerText = album.event_name + (album.event_date ? ` (${new Date(album.event_date).toLocaleDateString()})` : '');
            const grid = document.getElementById('lightbox-images-grid');
            grid.innerHTML = '';

            const images = album.images || [];
            if (images.length === 0) {
                grid.innerHTML = '<div style="color:white; grid-column:1/-1; text-align:center; padding:40px;">No photos in this album.</div>';
            } else {
                images.forEach(img => {
                    const item = document.createElement('div');
                    item.style.cssText = 'position:relative; border-radius:12px; overflow:hidden; background:#222; box-shadow: 0 4px 12px rgba(0,0,0,0.5); border:1px solid rgba(255,255,255,0.1);';
                    item.innerHTML = `
                        <img src="${img.image_url}" style="width:100%; height:180px; object-fit:cover; display:block;">
                        <div style="position:absolute; bottom:6px; right:6px; display:flex; gap:4px;">
                            <a href="${img.image_url}" target="_blank" class="btn" style="background:rgba(0,0,0,0.6); color:white; padding:4px 8px; font-size:11px; border-radius:4px; text-decoration:none;">View Full</a>
                            <button onclick="deleteSingleGalleryImage(${img.id}, ${album.id})" style="background:#ff4d4f; color:white; border:none; padding:4px 8px; font-size:11px; border-radius:4px; cursor:pointer;" title="Delete Photo">🗑️</button>
                        </div>
                    `;
                    grid.appendChild(item);
                });
            }

            document.getElementById('gallery-lightbox-modal').classList.remove('hidden');
        }

        function closeGalleryLightbox() {
            document.getElementById('gallery-lightbox-modal').classList.add('hidden');
        }

        async function deleteSingleGalleryImage(imageId, albumId) {
            if (!confirm('Remove this photo from the album?')) return;

            try {
                const res = await apiRequest(`/gallery-images/${imageId}`, {
                    method: 'DELETE'
                });

                if (res.success || res.message) {
                    showToast('Photo removed!', 'success');
                    await fetchGalleries();
                    openGalleryLightbox(albumId);
                } else {
                    showToast(res.message || 'Failed to remove photo.', 'error');
                }
            } catch (err) {
                showToast(err.message || 'Action failed.', 'error');
            }
        }

        // ==========================================
        // AI PERFORMANCE INSIGHTS LOGIC
        // ==========================================
        let currentAiPerfReports = [];
        let currentAiPerfStudent = null;
        let currentAiActiveReport = null;

        async function initAiPerformanceView() {
            const adminObj = JSON.parse(localStorage.getItem('admin_user') || '{}');
            const role = ((adminObj && adminObj.role) || localStorage.getItem('user_role') || 'admin').toLowerCase();

            const gradeSelect = document.getElementById('ai-perf-grade');
            const studentSelect = document.getElementById('ai-perf-student');
            const generateBtn = document.getElementById('ai-perf-generate-btn');
            const printBtn = document.getElementById('ai-perf-print-btn');
            const emptyState = document.getElementById('ai-perf-empty-state');
            const loadingState = document.getElementById('ai-perf-loading-state');
            const contentState = document.getElementById('ai-perf-content-state');

            if (!gradeSelect) return;

            // Reset UI to default empty state
            gradeSelect.innerHTML = '<option value="">Select Grade</option>';
            studentSelect.innerHTML = '<option value="">Select Student</option>';
            studentSelect.disabled = true;
            generateBtn.disabled = true;
            if (printBtn) printBtn.classList.add('hidden');
            emptyState.classList.remove('hidden');
            loadingState.classList.add('hidden');
            contentState.classList.add('hidden');
            contentState.innerHTML = '';

            let availableGrades = [];

            if (role === 'teacher') {
                if (!window.currentTeacherGrades || window.currentTeacherGrades.length === 0) {
                    await setupTeacherDashboard(adminObj);
                }
                if (window.currentTeacherGrades && window.currentTeacherGrades.length > 0) {
                    availableGrades = window.currentTeacherGrades;
                } else if (window.currentTeacherClassGrades && window.currentTeacherClassGrades.length > 0) {
                    availableGrades = window.currentTeacherClassGrades;
                }
            } else {
                // Admin: all grades
                if (!gradesData || gradesData.length === 0) {
                    try {
                        const gRes = await apiRequest('/admin/grades');
                        if (gRes.success && Array.isArray(gRes.data)) gradesData = gRes.data;
                    } catch(e) {}
                }
                const gradeSet = new Set();
                if (gradesData && gradesData.length > 0) {
                    gradesData.forEach(g => {
                        const raw = (g.name || '').replace(/Grade /i, '').trim();
                        if (raw) gradeSet.add(raw);
                    });
                }
                for (let i = 1; i <= 12; i++) gradeSet.add(String(i));
                availableGrades = Array.from(gradeSet).sort((a, b) => parseInt(a) - parseInt(b)).map(g => `Grade ${g}`);
            }

            availableGrades.forEach(gName => {
                const opt = document.createElement('option');
                const rawVal = gName.replace(/Grade /i, '').trim();
                opt.value = rawVal;
                opt.textContent = gName.toLowerCase().startsWith('grade') ? gName : `Grade ${gName}`;
                gradeSelect.appendChild(opt);
            });
        }

        async function onAiPerfGradeChanged() {
            const gradeSelect = document.getElementById('ai-perf-grade');
            const studentSelect = document.getElementById('ai-perf-student');
            const generateBtn = document.getElementById('ai-perf-generate-btn');
            const printBtn = document.getElementById('ai-perf-print-btn');
            const emptyState = document.getElementById('ai-perf-empty-state');
            const contentState = document.getElementById('ai-perf-content-state');

            const selectedGrade = gradeSelect ? (gradeSelect.value || '').trim() : '';

            // Reset student and report state
            studentSelect.innerHTML = '<option value="">Select Student</option>';
            studentSelect.disabled = true;
            generateBtn.disabled = true;
            if (printBtn) printBtn.classList.add('hidden');
            emptyState.classList.remove('hidden');
            contentState.classList.add('hidden');
            contentState.innerHTML = '';

            if (!selectedGrade) return;

            studentSelect.innerHTML = '<option value="">Loading students...</option>';

            try {
                if (!examStudentsLoaded || !examStudentsList || examStudentsList.length === 0) {
                    const usersRes = await apiRequest('/admin/users?all=true');
                    if (usersRes.success && Array.isArray(usersRes.data)) {
                        examStudentsList = usersRes.data.filter(u => u.role && u.role.toLowerCase() === 'student' && u.student);
                        examStudentsLoaded = true;
                    }
                }

                const studentsInGrade = (examStudentsList || []).filter(u => {
                    const stGrade = u.student ? String(u.student.grade || '').trim() : '';
                    return isExactGradeMatch(selectedGrade, stGrade);
                });

                studentSelect.innerHTML = `<option value="">Select Student (${studentsInGrade.length})</option>`;
                if (studentsInGrade.length === 0) {
                    studentSelect.innerHTML = '<option value="">No students found in this grade</option>';
                    studentSelect.disabled = true;
                    return;
                }

                studentsInGrade.sort((a, b) => (a.name || '').localeCompare(b.name || ''));
                studentsInGrade.forEach(u => {
                    const opt = document.createElement('option');
                    opt.value = u.student.id;
                    opt.textContent = `${u.name} (${u.student.student_id || ('#' + u.id)})`;
                    opt.dataset.userName = u.name;
                    opt.dataset.grade = u.student.grade || selectedGrade;
                    studentSelect.appendChild(opt);
                });

                studentSelect.disabled = false;
            } catch (err) {
                studentSelect.innerHTML = '<option value="">Failed to load students</option>';
            }
        }

        async function onAiPerfStudentChanged() {
            const studentSelect = document.getElementById('ai-perf-student');
            const generateBtn = document.getElementById('ai-perf-generate-btn');
            const emptyState = document.getElementById('ai-perf-empty-state');
            const contentState = document.getElementById('ai-perf-content-state');
            const printBtn = document.getElementById('ai-perf-print-btn');

            const studentId = studentSelect ? (studentSelect.value || '').trim() : '';

            if (!studentId) {
                generateBtn.disabled = true;
                if (printBtn) printBtn.classList.add('hidden');
                emptyState.classList.remove('hidden');
                contentState.classList.add('hidden');
                return;
            }

            generateBtn.disabled = false;
            await loadAiPerformanceReport(studentId);
        }

        function onAiPerfFiltersChanged() {
            const studentSelect = document.getElementById('ai-perf-student');
            if (studentSelect && studentSelect.value) {
                // User can click Generate Report with the updated filters
            }
        }

        async function loadAiPerformanceReport(studentId) {
            const emptyState = document.getElementById('ai-perf-empty-state');
            const loadingState = document.getElementById('ai-perf-loading-state');
            const contentState = document.getElementById('ai-perf-content-state');
            const printBtn = document.getElementById('ai-perf-print-btn');

            emptyState.classList.add('hidden');
            contentState.classList.add('hidden');
            loadingState.classList.remove('hidden');
            if (printBtn) printBtn.classList.add('hidden');

            try {
                const res = await apiRequest(`/ai-performance/reports/${studentId}`);
                loadingState.classList.add('hidden');

                if (res.success && res.data) {
                    currentAiPerfReports = res.data.history || [];
                    currentAiPerfStudent = res.data.student;

                    if (res.data.latest) {
                        currentAiActiveReport = res.data.latest;
                        renderAiPerformanceReport(res.data.latest, res.data.student, res.data.history);
                    } else {
                        // No reports yet for this student
                        currentAiActiveReport = null;
                        renderNoReportState(res.data.student);
                    }
                } else {
                    renderNoReportState();
                }
            } catch (err) {
                loadingState.classList.add('hidden');
                showToast(err.message || 'Failed to fetch student AI report.', 'error');
                emptyState.classList.remove('hidden');
            }
        }

        function renderNoReportState(studentObj = null) {
            const contentState = document.getElementById('ai-perf-content-state');
            const printBtn = document.getElementById('ai-perf-print-btn');
            if (printBtn) printBtn.classList.add('hidden');

            const sName = studentObj ? studentObj.name : 'this student';
            contentState.innerHTML = `
                <div class="glass-card" style="text-align: center; padding: 50px 20px; background: rgba(255,255,255,0.7); border-radius: 16px; border: 1.5px dashed #cbd5e1;">
                    <div style="font-size: 40px; margin-bottom: 12px;">📊</div>
                    <h3 style="color: #1e293b; font-size: 17px; margin: 0 0 6px 0; font-weight: 700;">No AI Report Generated Yet</h3>
                    <p style="color: #64748b; font-size: 13px; max-width: 450px; margin: 0 auto 20px auto; line-height: 1.5;">
                        No previous evaluation exists for <strong>${sName}</strong>. Click below to analyze exam results and homework records with Smart School AI.
                    </p>
                    <button class="btn-toggle-fee pay" onclick="triggerGenerateAiReport()" style="padding: 11px 24px; font-weight: 700; font-size: 13px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%); border: none; border-radius: 8px; color: white; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(99,102,241,0.35);">
                        <svg style="width:16px; height:16px; fill:currentColor;" viewBox="0 0 24 24"><path d="M19 9l1.25-2.75L23 5l-2.75-1.25L19 1l-1.25 2.75L15 5l2.75 1.25L19 9zm-7.5.5L9 4 6.5 9.5 1 12l5.5 2.5L9 20l2.5-5.5L17 12l-5.5-2.5zM19 15l-1.25 2.75L15 19l2.75 1.25L19 23l1.25-2.75L23 19l-2.75-1.25L19 15z"/></svg>
                        Generate Initial AI Report
                    </button>
                </div>
            `;
            contentState.classList.remove('hidden');
        }

        async function triggerGenerateAiReport() {
            const studentSelect = document.getElementById('ai-perf-student');
            const yearSelect = document.getElementById('ai-perf-year');
            const termSelect = document.getElementById('ai-perf-term');
            const generateBtn = document.getElementById('ai-perf-generate-btn');
            const loadingState = document.getElementById('ai-perf-loading-state');
            const contentState = document.getElementById('ai-perf-content-state');
            const emptyState = document.getElementById('ai-perf-empty-state');
            const printBtn = document.getElementById('ai-perf-print-btn');

            const studentId = studentSelect ? studentSelect.value : '';
            const year = yearSelect ? yearSelect.value : '2026';
            const term = termSelect ? termSelect.value : 'All Terms';

            if (!studentId) {
                showToast('Please select a student first.', 'error');
                return;
            }

            emptyState.classList.add('hidden');
            contentState.classList.add('hidden');
            loadingState.classList.remove('hidden');
            if (printBtn) printBtn.classList.add('hidden');

            const origText = generateBtn.innerHTML;
            generateBtn.disabled = true;
            generateBtn.innerHTML = `
                <div class="spinner" style="width:14px; height:14px; border-width:2px; border-color:white; border-top-color:transparent; border-radius:50%; animation:spin 1s linear infinite; display:inline-block;"></div>
                <span>Generating...</span>
            `;

            try {
                const res = await apiRequest('/ai-performance/generate', {
                    method: 'POST',
                    body: JSON.stringify({
                        student_id: studentId,
                        academic_year: year,
                        term: term
                    })
                });

                if (res.success && res.data) {
                    showToast('AI Performance Report generated successfully!', 'success');
                    await loadAiPerformanceReport(studentId);
                } else {
                    showToast(res.message || 'Failed to generate report.', 'error');
                    loadingState.classList.add('hidden');
                    if (currentAiActiveReport) {
                        contentState.classList.remove('hidden');
                    } else {
                        renderNoReportState(currentAiPerfStudent);
                    }
                }
            } catch (err) {
                loadingState.classList.add('hidden');
                showToast(err.message || 'Failed to connect to AI service.', 'error');
                if (currentAiActiveReport) {
                    contentState.classList.remove('hidden');
                } else {
                    emptyState.classList.remove('hidden');
                }
            } finally {
                generateBtn.disabled = false;
                generateBtn.innerHTML = origText;
            }
        }

        function switchAiReportHistory(reportId) {
            const report = currentAiPerfReports.find(r => r.id === reportId);
            if (report) {
                currentAiActiveReport = report;
                renderAiPerformanceReport(report, currentAiPerfStudent, currentAiPerfReports);
            }
        }

        function renderAiPerformanceReport(report, studentObj, history = []) {
            const contentState = document.getElementById('ai-perf-content-state');
            const printBtn = document.getElementById('ai-perf-print-btn');
            const downloadBtn = document.getElementById('ai-perf-download-btn');
            if (printBtn) printBtn.classList.remove('hidden');
            if (downloadBtn) downloadBtn.classList.remove('hidden');

            currentAiActiveReport = report;

            const sName = studentObj ? studentObj.name : 'Student';
            const sId = studentObj ? (studentObj.student_id || ('#' + studentObj.id)) : '—';
            const sGrade = studentObj ? ('Grade ' + String(studentObj.grade || '').replace(/Grade /i, '')) : '—';
            const year = report.academic_year || '2026';
            const term = report.term || 'All Terms';
            const generatedDate = report.created_at ? new Date(report.created_at).toLocaleString() : 'Recently';

            const metrics = report.metrics || {};
            const avg = metrics.average_marks !== null && metrics.average_marks !== undefined ? `${metrics.average_marks}%` : 'N/A';
            const totalExams = metrics.total_exams || 0;
            const assignments = metrics.total_assignments_submitted || 0;
            const attendance = metrics.attendance_percentage !== null && metrics.attendance_percentage !== undefined ? `${metrics.attendance_percentage}%` : 'N/A';

            // Strengths list
            const strengths = Array.isArray(report.strengths) ? report.strengths : [];
            const strengthsHtml = strengths.map(st => `
                <div style="display: flex; gap: 10px; align-items: flex-start; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 12px 15px; margin-bottom: 8px;">
                    <span style="display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:50%; background:#22c55e; color:white; font-size:12px; font-weight:bold; flex-shrink:0;">✓</span>
                    <span style="font-size: 13.5px; color: #15803d; line-height: 1.5; font-weight: 500;">${st}</span>
                </div>
            `).join('') || '<p style="color:#64748b; font-size:13px; margin:0;">No specific strengths recorded.</p>';

            // Weaknesses list
            const weaknesses = Array.isArray(report.weaknesses) ? report.weaknesses : [];
            const weaknessesHtml = weaknesses.map(wk => `
                <div style="display: flex; gap: 10px; align-items: flex-start; background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 12px 15px; margin-bottom: 8px;">
                    <span style="display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:50%; background:#f59e0b; color:white; font-size:12px; font-weight:bold; flex-shrink:0;">!</span>
                    <span style="font-size: 13.5px; color: #b45309; line-height: 1.5; font-weight: 500;">${wk}</span>
                </div>
            `).join('') || '<p style="color:#64748b; font-size:13px; margin:0;">No specific weak areas flagged.</p>';

            // Improvement Suggestions
            const suggestions = Array.isArray(report.improvement_suggestions) ? report.improvement_suggestions : [];
            const suggestionsHtml = suggestions.map((sug, idx) => `
                <div style="display: flex; gap: 12px; align-items: flex-start; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 16px; margin-bottom: 10px;">
                    <span style="display:inline-flex; align-items:center; justify-content:center; width:24px; height:24px; border-radius:50%; background:#3b82f6; color:white; font-size:12px; font-weight:700; flex-shrink:0;">${idx + 1}</span>
                    <span style="font-size: 13.5px; color: #334155; line-height: 1.5;">${sug}</span>
                </div>
            `).join('') || '<p style="color:#64748b; font-size:13px; margin:0;">No specific recommendations recorded.</p>';

            // History selector tabs
            let historyTabsHtml = '';
            if (history && history.length > 1) {
                historyTabsHtml = `
                    <div style="margin-bottom: 20px; background: white; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Previous Reports:</span>
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            ${history.map((h, i) => {
                                const isCurrent = h.id === report.id;
                                const dateStr = h.created_at ? new Date(h.created_at).toLocaleDateString() : `#${h.id}`;
                                const activeStyle = isCurrent 
                                    ? 'background: #6366f1; color: white; border-color: #6366f1;' 
                                    : 'background: #f8fafc; color: #475569; border-color: #cbd5e1;';
                                return `
                                    <button onclick="switchAiReportHistory(${h.id})" style="padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; cursor: pointer; border: 1px solid; ${activeStyle} transition: all 0.2s;">
                                        ${i === 0 ? '★ Latest' : `Report ${dateStr}`}
                                    </button>
                                `;
                            }).join('')}
                        </div>
                    </div>
                `;
            }

            contentState.innerHTML = `
                ${historyTabsHtml}

                <!-- TOP PROFILE BANNER -->
                <div class="glass-card" style="margin-bottom: 20px; padding: 22px 25px; border-radius: 16px; background: linear-gradient(135deg, rgba(99,102,241,0.08) 0%, rgba(139,92,246,0.08) 50%, rgba(217,70,239,0.08) 100%); border: 1.5px solid rgba(99,102,241,0.2);">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <div style="width: 54px; height: 54px; border-radius: 16px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; display: flex; align-items: center; justify-content: center; font-size: 26px; box-shadow: 0 4px 12px rgba(99,102,241,0.3);">
                                🎓
                            </div>
                            <div>
                                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                    <h2 style="margin: 0; font-size: 20px; color: #0f172a; font-weight: 700;">${sName}</h2>
                                    <span style="padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; background: #e0e7ff; color: #4338ca;">${sId}</span>
                                    <span style="padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; background: #f1f5f9; color: #334155;">${sGrade}</span>
                                </div>
                                <div style="margin-top: 5px; font-size: 13px; color: #64748b; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                                    <span>📅 Academic Year: <strong>${year}</strong></span>
                                    <span>•</span>
                                    <span>🏷️ Period: <strong>${term}</strong></span>
                                    <span>•</span>
                                    <span>🕒 Evaluated: <strong>${generatedDate}</strong></span>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <button onclick="downloadAiPerformancePdf()" style="padding: 8px 16px; font-size: 12px; font-weight: 700; border-radius: 8px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 10px rgba(99,102,241,0.25); transition: all 0.2s;">
                                <svg style="width:15px; height:15px; fill:currentColor;" viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                                <span>Download PDF</span>
                            </button>
                            <button onclick="printAiPerformanceReport()" style="padding: 8px 16px; font-size: 12px; font-weight: 700; border-radius: 8px; background: white; color: #0077be; border: 1.5px solid #bce3fb; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.04); transition: all 0.2s;">
                                <svg style="width:15px; height:15px; fill:currentColor;" viewBox="0 0 24 24"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
                                <span>Print / Save PDF</span>
                            </button>
                            <button class="btn-toggle-fee pay" onclick="triggerGenerateAiReport()" style="padding: 8px 16px; font-size: 12px; font-weight: 700; border-radius: 8px; background: white; color: #6366f1; border: 1.5px solid #c7d2fe; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
                                <span>↻ Re-evaluate &amp; Update</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- KEY METRIC CARDS -->
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 20px;">
                    <div class="glass-card" style="padding: 18px 22px; background: white; border-radius: 12px; border: 1px solid #e2e8f0; border-left: 4px solid #0077be;">
                        <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Total Exams</span>
                        <div style="font-size: 26px; font-weight: 800; color: #1e293b; margin-top: 4px;">${totalExams}</div>
                        <span style="font-size: 12px; color: #64748b;">Subjects evaluated</span>
                    </div>
                    <div class="glass-card" style="padding: 18px 22px; background: white; border-radius: 12px; border: 1px solid #e2e8f0; border-left: 4px solid #10b981;">
                        <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Assignments</span>
                        <div style="font-size: 26px; font-weight: 800; color: #1e293b; margin-top: 4px;">${assignments}</div>
                        <span style="font-size: 12px; color: #64748b;">Tasks submitted</span>
                    </div>
                </div>

                <!-- OVERALL PERFORMANCE & GROWTH GRID -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <!-- Overall Performance -->
                    <div class="glass-card" style="padding: 22px 24px; background: white; border-radius: 14px; border: 1px solid #e2e8f0;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                            <span style="font-size: 18px;">📋</span>
                            <h3 style="margin: 0; font-size: 16px; color: #1e293b; font-weight: 700;">Overall Academic Performance</h3>
                        </div>
                        <p style="font-size: 14px; color: #334155; line-height: 1.6; margin: 0; white-space: pre-line;">
                            ${report.overall_performance || 'No detailed evaluation recorded.'}
                        </p>
                    </div>

                    <!-- Growth & Progress Trajectory -->
                    <div class="glass-card" style="padding: 22px 24px; background: white; border-radius: 14px; border: 1px solid #e2e8f0;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                            <span style="font-size: 18px;">📈</span>
                            <h3 style="margin: 0; font-size: 16px; color: #1e293b; font-weight: 700;">Growth &amp; Progress Trajectory</h3>
                        </div>
                        <p style="font-size: 14px; color: #334155; line-height: 1.6; margin: 0;">
                            ${report.growth_progress || 'Performance trajectory remains stable across evaluated terms.'}
                        </p>
                    </div>
                </div>

                <!-- STRENGTHS & WEAKNESSES GRID -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <!-- Key Strengths -->
                    <div class="glass-card" style="padding: 22px 24px; background: white; border-radius: 14px; border: 1px solid #e2e8f0;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                            <span style="font-size: 18px;">🌟</span>
                            <h3 style="margin: 0; font-size: 16px; color: #166534; font-weight: 700;">Academic Strengths</h3>
                        </div>
                        ${strengthsHtml}
                    </div>

                    <!-- Focus Areas / Weaknesses -->
                    <div class="glass-card" style="padding: 22px 24px; background: white; border-radius: 14px; border: 1px solid #e2e8f0;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                            <span style="font-size: 18px;">🎯</span>
                            <h3 style="margin: 0; font-size: 16px; color: #9a3412; font-weight: 700;">Areas for Improvement</h3>
                        </div>
                        ${weaknessesHtml}
                    </div>
                </div>

                <!-- IMPROVEMENT SUGGESTIONS -->
                <div class="glass-card" style="padding: 22px 24px; background: white; border-radius: 14px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                        <span style="font-size: 18px;">💡</span>
                        <h3 style="margin: 0; font-size: 16px; color: #1d4ed8; font-weight: 700;">Actionable Improvement Guidance</h3>
                    </div>
                    ${suggestionsHtml}
                </div>

                <!-- CLOSING SUMMARY -->
                ${report.summary ? `
                    <div class="glass-card" style="padding: 18px 22px; background: linear-gradient(135deg, rgba(99,102,241,0.06), rgba(217,70,239,0.06)); border-radius: 12px; border: 1px dashed rgba(99,102,241,0.3); text-align: center;">
                        <span style="font-size: 12px; font-weight: 700; color: #6366f1; text-transform: uppercase;">Counselor Summary</span>
                        <p style="font-size: 14px; color: #334155; margin: 6px 0 0 0; font-style: italic;">
                            "${report.summary}"
                        </p>
                    </div>
                ` : ''}
            `;

            contentState.classList.remove('hidden');
        }

        function setAiPerfActionButtonsVisible(visible) {
            const printBtn = document.getElementById('ai-perf-print-btn');
            const downloadBtn = document.getElementById('ai-perf-download-btn');
            if (printBtn) {
                if (visible) printBtn.classList.remove('hidden');
                else printBtn.classList.add('hidden');
            }
            if (downloadBtn) {
                if (visible) downloadBtn.classList.remove('hidden');
                else downloadBtn.classList.add('hidden');
            }
        }

        function generateAiReportHtml(report, studentObj) {
            const sName = studentObj.name || 'Student';
            const sId = studentObj.student_id || ('#' + studentObj.id);
            const sGrade = 'Grade ' + String(studentObj.grade || '').replace(/Grade /i, '');
            const sClass = studentObj.class ? `Class ${studentObj.class}` : '';
            const gradeDisplay = [sGrade, sClass].filter(Boolean).join(' - ');
            const year = report.academic_year || '2026';
            const term = report.term || 'All Terms';
            const metrics = report.metrics || {};
            const avg = metrics.average_marks !== null && metrics.average_marks !== undefined ? `${metrics.average_marks}%` : 'N/A';
            const totalExams = metrics.total_exams || 0;
            const assignments = metrics.total_assignments_submitted || 0;
            const attendance = metrics.attendance_percentage !== null && metrics.attendance_percentage !== undefined ? `${metrics.attendance_percentage}%` : 'N/A';

            const strengths = Array.isArray(report.strengths) ? report.strengths : [];
            const weaknesses = Array.isArray(report.weaknesses) ? report.weaknesses : [];
            const suggestions = Array.isArray(report.improvement_suggestions) ? report.improvement_suggestions : [];

            return `
                <div style="font-family: 'Segoe UI', Arial, sans-serif; color: #1e293b; padding: 28px 32px; font-size: 13px; line-height: 1.55; background: #ffffff; max-width: 800px; margin: 0 auto; box-sizing: border-box;">
                    <!-- HEADER -->
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #6366f1; padding-bottom: 14px; margin-bottom: 20px;">
                        <div>
                            <div style="font-size: 24px; font-weight: 800; color: #4338ca; letter-spacing: -0.5px;">Smart School</div>
                            <div style="font-size: 12px; color: #64748b; font-weight: 600; margin-top: 2px;">AI-Powered Student Academic Performance Report</div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 10px; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Evaluation Date</div>
                            <div style="font-size: 13px; font-weight: 700; color: #1e293b; margin-top: 2px;">${new Date(report.created_at || Date.now()).toLocaleDateString()}</div>
                        </div>
                    </div>

                    <!-- STUDENT META INFO -->
                    <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 14px 18px; margin-bottom: 20px; display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 12px; font-size: 13px;">
                        <div>
                            <span style="color:#64748b; font-size:10px; display:block; font-weight:700; text-transform:uppercase; letter-spacing: 0.5px;">Student Name</span>
                            <strong style="color: #0f172a; font-size: 14px;">${sName}</strong> <span style="color:#6366f1; font-weight:700; font-size:12px;">(${sId})</span>
                        </div>
                        <div>
                            <span style="color:#64748b; font-size:10px; display:block; font-weight:700; text-transform:uppercase; letter-spacing: 0.5px;">Grade / Class</span>
                            <strong style="color: #0f172a;">${gradeDisplay}</strong>
                        </div>
                        <div>
                            <span style="color:#64748b; font-size:10px; display:block; font-weight:700; text-transform:uppercase; letter-spacing: 0.5px;">Academic Year</span>
                            <strong style="color: #0f172a;">${year}</strong>
                        </div>
                        <div>
                            <span style="color:#64748b; font-size:10px; display:block; font-weight:700; text-transform:uppercase; letter-spacing: 0.5px;">Evaluation Period</span>
                            <strong style="color: #0f172a;">${term}</strong>
                        </div>
                    </div>

                    <!-- KEY KPI CARDS -->
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 24px;">
                        <div style="border: 1px solid #e2e8f0; border-top: 3.5px solid #0077be; border-radius: 8px; padding: 12px 16px; text-align: center; background: #fff;">
                            <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Total Exams</div>
                            <div style="font-size: 22px; font-weight: 800; color: #1e293b; margin-top: 3px;">${totalExams}</div>
                            <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Subjects Evaluated</div>
                        </div>
                        <div style="border: 1px solid #e2e8f0; border-top: 3.5px solid #10b981; border-radius: 8px; padding: 12px 16px; text-align: center; background: #fff;">
                            <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Assignments</div>
                            <div style="font-size: 22px; font-weight: 800; color: #1e293b; margin-top: 3px;">${assignments}</div>
                            <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Tasks Submitted</div>
                        </div>
                    </div>

                    <!-- 1. OVERALL PERFORMANCE -->
                    <div style="margin-bottom: 20px;">
                        <div style="font-size: 13.5px; font-weight: 700; color: #1e293b; border-bottom: 1.5px solid #e2e8f0; padding-bottom: 4px; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                            <span>📋</span> <span>1. Overall Academic Standing &amp; Evaluation</span>
                        </div>
                        <p style="margin: 0; color: #334155; line-height: 1.6; white-space: pre-line;">${report.overall_performance || '—'}</p>
                    </div>

                    <!-- 2. GROWTH & PROGRESS -->
                    <div style="margin-bottom: 20px;">
                        <div style="font-size: 13.5px; font-weight: 700; color: #1e293b; border-bottom: 1.5px solid #e2e8f0; padding-bottom: 4px; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                            <span>📈</span> <span>2. Growth &amp; Performance Trajectory</span>
                        </div>
                        <p style="margin: 0; color: #334155; line-height: 1.6;">${report.growth_progress || '—'}</p>
                    </div>

                    <!-- 3. STRENGTHS -->
                    <div style="margin-bottom: 20px;">
                        <div style="font-size: 13.5px; font-weight: 700; color: #1e293b; border-bottom: 1.5px solid #e2e8f0; padding-bottom: 4px; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                            <span>🌟</span> <span>3. Key Academic Strengths</span>
                        </div>
                        ${strengths.length > 0 ? strengths.map(s => `
                            <div style="display: flex; gap: 10px; align-items: flex-start; padding: 8px 12px; margin-bottom: 6px; border-radius: 6px; background: #f0fdf4; border-left: 3.5px solid #22c55e; color: #15803d; font-size: 12.5px;">
                                <span style="font-weight: bold; flex-shrink: 0;">✓</span>
                                <span style="line-height: 1.5;">${s}</span>
                            </div>
                        `).join('') : '<p style="color:#64748b; font-size:12.5px; margin:0;">—</p>'}
                    </div>

                    <!-- 4. WEAKNESSES -->
                    <div style="margin-bottom: 20px;">
                        <div style="font-size: 13.5px; font-weight: 700; color: #1e293b; border-bottom: 1.5px solid #e2e8f0; padding-bottom: 4px; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                            <span>🎯</span> <span>4. Areas Requiring Focus &amp; Improvement</span>
                        </div>
                        ${weaknesses.length > 0 ? weaknesses.map(w => `
                            <div style="display: flex; gap: 10px; align-items: flex-start; padding: 8px 12px; margin-bottom: 6px; border-radius: 6px; background: #fffbeb; border-left: 3.5px solid #f59e0b; color: #b45309; font-size: 12.5px;">
                                <span style="font-weight: bold; flex-shrink: 0;">!</span>
                                <span style="line-height: 1.5;">${w}</span>
                            </div>
                        `).join('') : '<p style="color:#64748b; font-size:12.5px; margin:0;">—</p>'}
                    </div>

                    <!-- 5. SUGGESTIONS -->
                    <div style="margin-bottom: 20px;">
                        <div style="font-size: 13.5px; font-weight: 700; color: #1e293b; border-bottom: 1.5px solid #e2e8f0; padding-bottom: 4px; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                            <span>💡</span> <span>5. Actionable Guidance for Student &amp; Parents</span>
                        </div>
                        ${suggestions.length > 0 ? suggestions.map((sg, i) => `
                            <div style="display: flex; gap: 10px; align-items: flex-start; padding: 8px 12px; margin-bottom: 6px; border-radius: 6px; background: #f8fafc; border-left: 3.5px solid #3b82f6; color: #1e293b; font-size: 12.5px;">
                                <span style="font-weight: 700; color: #2563eb; flex-shrink: 0;">${i + 1}.</span>
                                <span style="line-height: 1.5;">${sg}</span>
                            </div>
                        `).join('') : '<p style="color:#64748b; font-size:12.5px; margin:0;">—</p>'}
                    </div>

                    <!-- SUMMARY -->
                    ${report.summary ? `
                        <div style="background: #f8fafc; padding: 12px 16px; border-radius: 8px; border: 1px dashed #cbd5e1; margin-bottom: 22px;">
                            <strong style="color: #4338ca; font-size: 12px; text-transform: uppercase;">Counselor Summary:</strong>
                            <div style="color: #334155; font-style: italic; margin-top: 4px; font-size: 13px; line-height: 1.5;">"${report.summary}"</div>
                        </div>
                    ` : ''}

                    <!-- FOOTER -->
                    <div style="margin-top: 25px; border-top: 1px solid #e2e8f0; padding-top: 12px; font-size: 11px; color: #94a3b8; text-align: center;">
                        Generated with Smart School AI Academic Intelligence System &bull; Official Confidential School Document
                    </div>
                </div>
            `;
        }

        async function ensureHtml2PdfLoaded() {
            if (typeof html2pdf !== 'undefined') return true;
            return new Promise((resolve) => {
                const script = document.createElement('script');
                script.src = '/js/html2pdf.bundle.min.js';
                script.onload = () => resolve(true);
                script.onerror = () => resolve(false);
                document.head.appendChild(script);
            });
        }

        async function downloadAiPerformancePdf() {
            if (!currentAiActiveReport) {
                showToast('No active report to download.', 'error');
                return;
            }
            const report = currentAiActiveReport;
            const studentObj = currentAiPerfStudent || {};
            const rawName = studentObj.name || 'Student';
            const cleanName = rawName.replace(/[^a-zA-Z0-9_-]/g, '_');
            const sId = (studentObj.student_id || ('ID_' + (studentObj.id || ''))).replace(/[^a-zA-Z0-9_-]/g, '_');
            const term = (report.term || 'Report').replace(/[^a-zA-Z0-9_-]/g, '_');
            const fileName = `${cleanName}_${sId}_${term}_AI_Report.pdf`;

            showToast('Generating PDF file, please wait...', 'info');

            const loaded = await ensureHtml2PdfLoaded();
            if (loaded && typeof html2pdf !== 'undefined') {
                const container = document.createElement('div');
                container.style.position = 'fixed';
                container.style.left = '0';
                container.style.top = '0';
                container.style.width = '760px';
                container.style.zIndex = '-9999';
                container.style.opacity = '1';
                container.style.pointerEvents = 'none';
                container.style.background = '#ffffff';
                container.innerHTML = generateAiReportHtml(report, studentObj);
                document.body.appendChild(container);

                const elementToConvert = container.firstElementChild || container;

                const opt = {
                    margin:       [10, 10, 10, 10],
                    filename:     fileName,
                    image:        { type: 'jpeg', quality: 0.98 },
                    html2canvas:  { scale: 2, useCORS: true, letterRendering: true, scrollY: 0, scrollX: 0 },
                    jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };

                try {
                    await html2pdf().set(opt).from(elementToConvert).save();
                    showToast('PDF file downloaded successfully!', 'success');
                } catch (err) {
                    console.error('html2pdf generation error:', err);
                    showToast('Direct download had an issue, opening print preview...', 'warning');
                    printAiPerformanceReport();
                } finally {
                    if (container && container.parentNode) {
                        container.parentNode.removeChild(container);
                    }
                }
            } else {
                showToast('PDF library unavailable, opening printable preview...', 'warning');
                printAiPerformanceReport();
            }
        }

        function printAiPerformanceReport() {
            if (!currentAiActiveReport) {
                showToast('No active report to print.', 'error');
                return;
            }
            const report = currentAiActiveReport;
            const studentObj = currentAiPerfStudent || {};
            const sName = studentObj.name || 'Student';

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>AI Academic Performance Report - ${sName}</title>
                    <style>
                        @page { size: A4 portrait; margin: 10mm; }
                        body { font-family: 'Segoe UI', Arial, sans-serif; color: #1e293b; margin: 0; padding: 0; font-size: 13px; line-height: 1.5; background: #fff; }
                        @media print {
                            body { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
                        }
                    </style>
                </head>
                <body>
                    ${generateAiReportHtml(report, studentObj)}
                    <script>
                        window.onload = function() {
                            window.print();
                        };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }
    </script>
</body>
</html>
