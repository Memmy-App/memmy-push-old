<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Lemmy\LemmyHelper;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushController extends Controller {
    public function getStatus (Request $request, LemmyHelper $lemmy): JsonResponse {
        $request->validate([
            "username" => "required|string",
            "instance" => "required|string",
            "authToken" => "required|string",
            "pushToken" => "required|string",
        ]);

        $lemmy->setup($request->input("username"), $request->input("instance"), $request->input("authToken"));

        if(!$lemmy->authenticate()) {
            return response()->json([
                "success" => false,
                "message" => "Authentication failed"
            ], 401);
        }

        $account = Account::where("username", $request->input("username"))
            ->where("instance", $request->input("instance"))
            ->first();

        if(!$account) {
            return response()->json([
                "success" => true,
                "result" => "not_configured",
                "message" => "Push notifications not configured."
            ]);
        }

        $token = $account->pushTokens()->firstWhere("push_token", $request->input("pushToken"));

        if(!$token) {
            return response()->json([
                "success" => true,
                "result" => "not_configured",
                "message" => "Push notifications not configured."
            ]);
        }

        return response()->json([
            "success" => true,
            "result" => "enabled",
            "message" => "Push notifications are enabled."
        ]);
    }

    public function enable(Request $request, LemmyHelper $lemmy): JsonResponse {
        $request->validate([
            "username" => "required|string",
            "instance" => "required|string",
            "authToken" => "required|string",
            "pushToken" => "required|string",
        ]);

        $lemmy->setup($request->input("username"), $request->input("instance"), $request->input("authToken"));

        if(!$lemmy->authenticate()) {
            return response()->json([
                "success" => false,
                "message" => "Authentication failed"
            ], 401);
        }

        $account = Account::where("username", $request->input("username"))
            ->where("instance", $request->input("instance"))
            ->first();

        if(!$account) {
            $account = Account::create([
                "username" => $request->input("username"),
                "instance" => $request->input("instance"),
                "auth_token" => $request->input("authToken")
            ]);
        }

        $token = $account->pushTokens()->firstWhere("push_token", $request->input("pushToken"));

        if(!$token) {
            $account->pushTokens()->create([
                "push_token" => $request->input("pushToken")
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "Successfully enabled push notifications."
        ]);
    }

    public function disable(Request $request, LemmyHelper $lemmy): JsonResponse{
        $request->validate([
            "username" => "required|string",
            "instance" => "required|string",
            "authToken" => "required|string",
            "pushToken" => "required|string",
        ]);

        $lemmy->setup($request->input("username"), $request->input("instance"), $request->input("authToken"));

        if(!$lemmy->authenticate()) {
            return response()->json([
                "success" => false,
                "message" => "Authentication failed"
            ], 401);
        }

        $account = Account::where("username", $request->input("username"))
            ->where("instance", $request->input("instance"))
            ->first();

        if(!$account) {
            return response()->json([
                "success" => true,
                "message" => "Successfully disabled push notifications."
            ]);
        }

        $token = $account->pushTokens()->firstWhere("push_token", $request->input("pushToken"));

        if($token) {
            $token->delete();
        }

        return response()->json([
            "success" => true,
            "message" => "Successfully disabled push notifications."
        ]);
    }
}
