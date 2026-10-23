<?php

/**
 * Authorization for the messages API (index.php?action=controller!method).
 *
 * The controllers load messages, attachments and files by id with no ownership check, so
 * every action is checked here before it runs. Administrators may do anything; other users
 * only reach messages delivered to them or their teams and attachments of patients in their
 * practices (see PerfectTranscription\PatientAccess). Unknown actions are denied.
 *
 * @package   OpenEMR
 * @link      https://www.open-emr.org
 * @author    Ken Chapple <ken@mi-squared.com>
 * @copyright Copyright (c) 2026 Ken Chapple <ken@mi-squared.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

declare(strict_types=1);

namespace Mi2\Messages;

use PerfectTranscription\PatientAccess;

final class AccessGuard
{
    /** Actions scoped to the current user by index.php (userId is forced to the session user). */
    private const SELF_SCOPED = [
        'message!fetch_all_message_filters',
        'message!fetch_all_message_meta_for_user',
        'message!fetch_all_teams',
        'message!fetch_all_users',
    ];

    /** Actions on one message, and the request parameter holding its id. */
    private const MESSAGE_ACTIONS = [
        'message!fetch_message' => 'id',
        'message!open_message' => 'messageId',
        'message!close_message' => 'messageId',
        'message!set_message_status' => 'messageId',
        'message!set_message_meta' => 'messageId',
        'message!add_reply_to_message' => 'messageId',
        'message!move_message' => 'messageId',
    ];

    /** @param array<string, mixed> $request */
    public static function check(array $request, int $userId): void
    {
        $access = PatientAccess::forUser($userId);
        if ($access->isAdmin()) {
            return;
        }
        $action = is_string($request['action'] ?? null) ? $request['action'] : '';
        $allowed = self::allowed($action, $request, $access);
        if ($allowed) {
            return;
        }
        error_log(sprintf('oe-mi-messages access guard: DENY user=%d action=%s', $userId, $action));
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Not authorized']);
        exit;
    }

    /** @param array<string, mixed> $request */
    private static function allowed(string $action, array $request, PatientAccess $access): bool
    {
        if (in_array($action, self::SELF_SCOPED, true)) {
            return true;
        }
        if (isset(self::MESSAGE_ACTIONS[$action])) {
            if (!$access->canAccessMessage(self::intParam($request, self::MESSAGE_ACTIONS[$action]))) {
                return false;
            }
            if ($action === 'message!move_message') {
                return $access->canUseFilter(self::intParam($request, 'fromFilterId'))
                    && $access->canUseFilter(self::intParam($request, 'toFilterId'));
            }
            return true;
        }
        switch ($action) {
            case 'message!fetch_messages':
                return $access->canUseFilter(self::intParam($request, 'filterId'));
            case 'message!bulk_download':
                $ids = array_filter(array_map('trim', explode(',', trim((string) ($request['messages'] ?? ''), '[] '))));
                if ($ids === []) {
                    return false;
                }
                foreach ($ids as $id) {
                    if (!ctype_digit($id) || !$access->canAccessMessage((int) $id)) {
                        return false;
                    }
                }
                return true;
            case 'file!fetch_html':
            case 'file!fetch_pdf':
                return $access->canAccessAttachment(self::intParam($request, 'attachmentId'));
            case 'file!push_html':
                return $access->canAccessFile(self::intParam($request, 'fileId'));
            case 'patient!setpid':
                return $access->canAccessPatient(self::intParam($request, 'pid'));
            default:
                // admin!* and anything not listed: administrators only.
                return false;
        }
    }

    /** @param array<string, mixed> $request */
    private static function intParam(array $request, string $key): int
    {
        $value = $request[$key] ?? null;
        return (is_int($value) || (is_string($value) && ctype_digit($value))) ? (int) $value : 0;
    }
}
