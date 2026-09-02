<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('get_mock_stats')) {
	function get_mock_stats() {
		$CI =& get_instance();
		if (isset($CI->db) && $CI->db->is_connected()) {
			$total_users = $CI->db->query("SELECT COUNT(*) as count FROM admins")->row_array();
			$active_sessions = $CI->db->query("SELECT COUNT(*) as count FROM ci_sessions")->row_array();
			$active_api_keys = $CI->db->query("SELECT COUNT(*) as count FROM api_keys WHERE status = 'active'")->row_array();
			$total_roles = array('count' => 2);
			
			return array(
				'totalUsers' => isset($total_users['count']) ? (int)$total_users['count'] : 1420,
				'activeUsers24h' => 892,
				'activeSessions' => isset($active_sessions['count']) ? (int)$active_sessions['count'] : 38,
				'totalRoles' => isset($total_roles['count']) ? (int)$total_roles['count'] : 7,
				'activeApiKeys' => isset($active_api_keys['count']) ? (int)$active_api_keys['count'] : 2,
				'securityRiskScore' => 14,
				'ci3SyncStatus' => 'synced',
				'lastDbSyncTime' => 'Just now (' . date('Y-m-d H:i:s') . ')'
			);
		}
		return array(
			'totalUsers' => 1420,
			'activeUsers24h' => 892,
			'activeSessions' => 38,
			'totalRoles' => 7,
			'activeApiKeys' => 2,
			'securityRiskScore' => 14,
			'ci3SyncStatus' => 'synced',
			'lastDbSyncTime' => 'Just now (' . date('Y-m-d H:i:s') . ')'
		);
	}
}

if (!function_exists('get_mock_users')) {
	function get_mock_users() {
		return array(
			array(
				'id' => 'USR-1092',
				'username' => 'athallah_root',
				'email' => 'athallah@toonhub.id',
				'fullName' => 'Athallah Rizq',
				'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
				'role' => 'SuperAdmin',
				'status' => 'active',
				'ci3PasswordHash' => '$2y$10$e8Z.rJ4S4xP0K/7Pz9O8XeYvH01j.qK8b7H1u6f7W8i9O0P1Q2R3S',
				'isMfaEnabled' => true,
				'lastLogin' => '2026-07-26 18:42:10',
				'ipAddress' => '180.252.120.88',
				'createdAt' => '2025-01-15 08:30:00'
			),
			array(
				'id' => 'USR-1093',
				'username' => 'bocildev',
				'email' => 'bocildev@toonhub.id',
				'fullName' => 'Bocil Developer',
				'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',
				'role' => 'ContentManager',
				'status' => 'active',
				'ci3PasswordHash' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
				'isMfaEnabled' => true,
				'lastLogin' => '2026-07-26 17:15:00',
				'ipAddress' => '180.252.120.90',
				'createdAt' => '2025-02-01 10:12:00'
			),
			array(
				'id' => 'USR-1094',
				'username' => 'mangaka_rio',
				'email' => 'rio@studiotoon.id',
				'fullName' => 'Rio Studio Head',
				'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop&q=80',
				'role' => 'ComicCreator',
				'status' => 'active',
				'ci3PasswordHash' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
				'isMfaEnabled' => false,
				'lastLogin' => '2026-07-25 21:05:40',
				'ipAddress' => '114.124.180.12',
				'createdAt' => '2025-02-14 14:20:00'
			),
			array(
				'id' => 'USR-1095',
				'username' => 'siti_moderator',
				'email' => 'siti@toonhub.id',
				'fullName' => 'Siti Rahma',
				'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80',
				'role' => 'CommunityModerator',
				'status' => 'active',
				'ci3PasswordHash' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
				'isMfaEnabled' => false,
				'lastLogin' => '2026-07-26 14:10:00',
				'ipAddress' => '180.252.121.05',
				'createdAt' => '2025-03-01 09:00:00'
			),
			array(
				'id' => 'USR-1096',
				'username' => 'vip_kevin',
				'email' => 'kevin.reader@gmail.com',
				'fullName' => 'Kevin Sanjaya',
				'avatar' => 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?w=150&auto=format&fit=crop&q=80',
				'role' => 'VipSubscriber',
				'status' => 'mfa_required',
				'ci3PasswordHash' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
				'isMfaEnabled' => true,
				'lastLogin' => '2026-07-20 11:30:15',
				'ipAddress' => '36.85.12.44',
				'createdAt' => '2025-03-10 16:45:00'
			),
			array(
				'id' => 'USR-1097',
				'username' => 'syndicate_bot',
				'email' => 'api@kakao-webtoon.partner',
				'fullName' => 'Kakao Syndication Bot',
				'avatar' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=150&auto=format&fit=crop&q=80',
				'role' => 'ApiPartner',
				'status' => 'active',
				'ci3PasswordHash' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
				'isMfaEnabled' => false,
				'lastLogin' => '2026-07-26 20:00:00',
				'ipAddress' => '203.0.113.195',
				'createdAt' => '2025-04-01 11:11:11'
			),
			array(
				'id' => 'USR-1098',
				'username' => 'suspicious_actor',
				'email' => 'hacker@darknet.io',
				'fullName' => 'Unknown Entity',
				'avatar' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80',
				'role' => 'GuestReader',
				'status' => 'suspended',
				'ci3PasswordHash' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
				'isMfaEnabled' => false,
				'lastLogin' => '2026-07-24 03:12:09',
				'ipAddress' => '185.220.101.5',
				'createdAt' => '2025-05-12 02:00:00'
			)
		);
	}
}

if (!function_exists('get_mock_roles')) {
	function get_mock_roles() {
		return array(
			array(
				'id' => 'ROLE-01',
				'name' => 'SuperAdmin',
				'displayName' => 'Super Administrator',
				'description' => 'Full root access to all security scopes, API keys, and database clusters.',
				'color' => 'red',
				'isSystemRole' => true,
				'permissions' => array('comics.create', 'comics.edit', 'comics.delete', 'episodes.upload', 'episodes.publish', 'users.view', 'users.manage', 'users.delete', 'api.keys_manage', 'api.telemetry_view', 'financial.payouts', 'system.logs', 'system.config'),
				'userCount' => 1
			),
			array(
				'id' => 'ROLE-02',
				'name' => 'ContentManager',
				'displayName' => 'Content Manager',
				'description' => 'Publishes webtoons, moderates episodes, manages banner campaigns.',
				'color' => 'cyan',
				'isSystemRole' => true,
				'permissions' => array('comics.create', 'comics.edit', 'episodes.upload', 'episodes.publish', 'users.view'),
				'userCount' => 1
			),
			array(
				'id' => 'ROLE-03',
				'name' => 'ComicCreator',
				'displayName' => 'Comic Creator / Author',
				'description' => 'Uploads draft chapters, views monetization stats, responds to comments.',
				'color' => 'emerald',
				'isSystemRole' => false,
				'permissions' => array('comics.create', 'comics.edit', 'episodes.upload'),
				'userCount' => 1
			),
			array(
				'id' => 'ROLE-04',
				'name' => 'CommunityModerator',
				'displayName' => 'Community Moderator',
				'description' => 'Flags spam comments, manages user bans, handles user reports.',
				'color' => 'amber',
				'isSystemRole' => false,
				'permissions' => array('users.view'),
				'userCount' => 1
			),
			array(
				'id' => 'ROLE-05',
				'name' => 'VipSubscriber',
				'displayName' => 'VIP Subscriber',
				'description' => 'Access to early-access fast pass episodes and ad-free reading.',
				'color' => 'purple',
				'isSystemRole' => false,
				'permissions' => array(),
				'userCount' => 1
			),
			array(
				'id' => 'ROLE-06',
				'name' => 'ApiPartner',
				'displayName' => 'External API Partner',
				'description' => 'Restricted machine-to-machine REST API access for content syndication.',
				'color' => 'blue',
				'isSystemRole' => false,
				'permissions' => array('api.telemetry_view'),
				'userCount' => 1
			),
			array(
				'id' => 'ROLE-07',
				'name' => 'GuestReader',
				'displayName' => 'Guest Reader',
				'description' => 'Read-only access to public webtoon catalog.',
				'color' => 'slate',
				'isSystemRole' => true,
				'permissions' => array(),
				'userCount' => 1
			)
		);
	}
}

if (!function_exists('get_permission_groups')) {
	function get_permission_groups() {
		return array(
			array(
				'category' => 'comics',
				'displayName' => 'Webtoon & Content',
				'description' => 'Permissions related to comic catalog and episode publishing.',
				'permissions' => array(
					array('key' => 'comics.create', 'label' => 'Create Webtoon Series', 'description' => 'Allows creating new webtoon title entries'),
					array('key' => 'comics.edit', 'label' => 'Edit Series Metadata', 'description' => 'Modify titles, tags, banners, and age ratings'),
					array('key' => 'comics.delete', 'label' => 'Delete Webtoon Series', 'description' => 'Permanently remove a webtoon series and all chapters'),
					array('key' => 'episodes.upload', 'label' => 'Upload Episodes', 'description' => 'Upload images, set release schedules, and fast-pass prices'),
					array('key' => 'episodes.publish', 'label' => 'Publish / Unpublish', 'description' => 'Toggle chapter visibility on ToonHub reader apps')
				)
			),
			array(
				'category' => 'users',
				'displayName' => 'User & IAM Management',
				'description' => 'Control reader profiles, role elevation, and MFA settings.',
				'permissions' => array(
					array('key' => 'users.view', 'label' => 'View User List', 'description' => 'Search and filter registered readers and creators'),
					array('key' => 'users.manage', 'label' => 'Manage User Accounts', 'description' => 'Edit user roles, toggle MFA, and suspend accounts'),
					array('key' => 'users.delete', 'label' => 'Delete User Accounts', 'description' => 'Hard delete user records and clear stored sessions')
				)
			),
			array(
				'category' => 'api',
				'displayName' => 'REST API & Tokens',
				'description' => 'Machine-to-machine tokens, rate limits, and API telemetry.',
				'permissions' => array(
					array('key' => 'api.keys_manage', 'label' => 'Manage API Keys', 'description' => 'Generate, revoke, and set rate limits on partner tokens'),
					array('key' => 'api.telemetry_view', 'label' => 'View API Telemetry', 'description' => 'Monitor API request velocity and error rates')
				)
			),
			array(
				'category' => 'financial',
				'displayName' => 'Financial & Payouts',
				'description' => 'Creator revenue sharing and subscription transactions.',
				'permissions' => array(
					array('key' => 'financial.payouts', 'label' => 'Process Creator Payouts', 'description' => 'Approve monthly ad-revenue sharing payouts')
				)
			),
			array(
				'category' => 'system',
				'displayName' => 'System & Security Audit',
				'description' => 'Audit log inspection and low-level CodeIgniter 3 hook configurations.',
				'permissions' => array(
					array('key' => 'system.logs', 'label' => 'View Security Audit Logs', 'description' => 'Inspect system logs, IP tracking, and risk alerts'),
					array('key' => 'system.config', 'label' => 'CI3 Core Config', 'description' => 'Modify database sync settings and hook parameters')
				)
			)
		);
	}
}

if (!function_exists('get_mock_apikeys')) {
	function get_mock_apikeys() {
		return array(
			array(
				'id' => 'KEY-01',
				'name' => 'Kakao Content Syndication Sync',
				'prefix' => 'th_live_9f82...',
				'secretMasked' => 'th_live_9f82********************3a1e',
				'ownerId' => 'USR-1097',
				'ownerName' => 'Kakao Syndication Bot',
				'scopes' => array('comics.read', 'episodes.read'),
				'rateLimit' => 300,
				'status' => 'active',
				'lastUsed' => '2026-07-26 20:00:00',
				'createdAt' => '2025-04-01'
			),
			array(
				'id' => 'KEY-02',
				'name' => 'Mobile Android Production App',
				'prefix' => 'th_live_12c4...',
				'secretMasked' => 'th_live_12c4********************88bc',
				'ownerId' => 'USR-1092',
				'ownerName' => 'Athallah Rizq',
				'scopes' => array('users.read', 'comics.read', 'episodes.read', 'auth.verify'),
				'rateLimit' => 600,
				'status' => 'active',
				'lastUsed' => '2026-07-26 20:25:00',
				'createdAt' => '2025-01-20'
			),
			array(
				'id' => 'KEY-03',
				'name' => 'Legacy Webhook Aggregator',
				'prefix' => 'th_test_77d1...',
				'secretMasked' => 'th_test_77d1********************90aa',
				'ownerId' => 'USR-1093',
				'ownerName' => 'Bocil Developer',
				'scopes' => array('system.logs'),
				'rateLimit' => 60,
				'status' => 'revoked',
				'lastUsed' => '2026-06-15 10:00:00',
				'createdAt' => '2025-02-10'
			)
		);
	}
}

if (!function_exists('get_mock_audit_logs')) {
	function get_mock_audit_logs() {
		return array(
			array(
				'id' => 'LOG-9821',
				'timestamp' => '2026-07-26 20:15:33',
				'actor' => 'athallah_root',
				'actorEmail' => 'athallah@toonhub.id',
				'action' => 'ROLE_PERMISSIONS_UPDATE',
				'resource' => 'ToonHub IAM Control Panel',
				'ipAddress' => '180.252.120.88',
				'userAgent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/126.0',
				'riskLevel' => 'warning',
				'status' => 'success',
				'details' => 'Memberikan izin [api.keys_manage] kepada peran [ContentManager].'
			),
			array(
				'id' => 'LOG-9820',
				'timestamp' => '2026-07-26 19:40:12',
				'actor' => 'syndicate_bot',
				'actorEmail' => 'api@kakao-webtoon.partner',
				'action' => 'REST_API_BEARER_AUTH',
				'resource' => 'ToonHub REST API /v1/episodes',
				'ipAddress' => '203.0.113.195',
				'userAgent' => 'KakaoSyndicateBot/2.1',
				'riskLevel' => 'low',
				'status' => 'success',
				'details' => 'Otentikasi API Key [KEY-01] berhasil. Rate limit 42/300 req/min.'
			),
			array(
				'id' => 'LOG-9819',
				'timestamp' => '2026-07-26 18:02:00',
				'actor' => 'suspicious_actor',
				'actorEmail' => 'hacker@darknet.io',
				'action' => 'BRUTE_FORCE_PREVENTION',
				'resource' => 'CI3 Auth Controller /login',
				'ipAddress' => '185.220.101.5',
				'userAgent' => 'Python-urllib/3.10',
				'riskLevel' => 'critical',
				'status' => 'blocked',
				'details' => 'Deteksi 15 percobaan login gagal berturut-turut. IP otomatis dilarang oleh IAM_Hook.php.'
			),
			array(
				'id' => 'LOG-9818',
				'timestamp' => '2026-07-26 17:15:05',
				'actor' => 'bocildev',
				'actorEmail' => 'bocildev@toonhub.id',
				'action' => 'USER_REGISTRATION',
				'resource' => 'ToonHub User DB',
				'ipAddress' => '180.252.120.90',
				'userAgent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
				'riskLevel' => 'medium',
				'status' => 'success',
				'details' => 'Berhasil mendaftarkan user baru @mangaka_rio (ComicCreator).'
			),
			array(
				'id' => 'LOG-9817',
				'timestamp' => '2026-07-26 14:00:22',
				'actor' => 'athallah_root',
				'actorEmail' => 'athallah@toonhub.id',
				'action' => 'CI3_HOOK_DEPLOYMENT',
				'resource' => 'application/hooks/IAM_Hook.php',
				'ipAddress' => '180.252.120.88',
				'userAgent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
				'riskLevel' => 'low',
				'status' => 'success',
				'details' => 'Memperbarui file hook IAM_Hook.php untuk verifikasi sesi CI3 post_controller_constructor.'
			)
		);
	}
}

if (!function_exists('get_mock_ci3_sessions')) {
	function get_mock_ci3_sessions() {
		return array(
			array(
				'sessionId' => 'ci_sess_90a1b2c3d4e5f67890123456789abcdef',
				'ipAddress' => '180.252.120.88',
				'userAgent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/126.0',
				'lastActivity' => '2 mins ago (2026-07-26 20:30:11)',
				'userId' => 'USR-1092',
				'username' => 'athallah_root',
				'role' => 'SuperAdmin',
				'dataPayload' => array(
					'__ci_last_regenerate' => 1785072000,
					'user_id' => 'USR-1092',
					'username' => 'athallah_root',
					'role' => 'SuperAdmin',
					'logged_in' => true
				)
			),
			array(
				'sessionId' => 'ci_sess_11223344556677889900aabbccddeeff',
				'ipAddress' => '180.252.120.90',
				'userAgent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
				'lastActivity' => '18 mins ago (2026-07-26 20:14:02)',
				'userId' => 'USR-1093',
				'username' => 'bocildev',
				'role' => 'ContentManager',
				'dataPayload' => array(
					'__ci_last_regenerate' => 1785071800,
					'user_id' => 'USR-1093',
					'username' => 'bocildev',
					'role' => 'ContentManager',
					'logged_in' => true
				)
			),
			array(
				'sessionId' => 'ci_sess_ffee0099887766554433221100aabbcc',
				'ipAddress' => '203.0.113.195',
				'userAgent' => 'KakaoSyndicateBot/2.1',
				'lastActivity' => '32 mins ago (2026-07-26 20:00:00)',
				'userId' => 'USR-1097',
				'username' => 'syndicate_bot',
				'role' => 'ApiPartner',
				'dataPayload' => array(
					'__ci_last_regenerate' => 1785071950,
					'user_id' => 'USR-1097',
					'username' => 'syndicate_bot',
					'role' => 'ApiPartner',
					'logged_in' => true
				)
			)
		);
	}
}

if (!function_exists('base_url')) {
	function base_url($path = '') {
		$base = '';
		if (isset($_SERVER['SCRIPT_NAME'])) {
			$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
		}
		$root = ($base === '' || $base === '/' || $base === '\\') ? '/' : $base . '/';
		return $root . ltrim($path, '/');
	}
}
