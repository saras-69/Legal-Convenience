<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $rewards = Reward::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('rewards.index', compact('rewards'));
    }

    public function redeem(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'points' => 'required|integer|min:100|max:' . $user->reward_points,
        ]);
        
        $pointsToRedeem = $request->points;
        
        // Deduct points from user
        $user->reward_points -= $pointsToRedeem;
        $user->save();
        
        // Create redemption record
        Reward::create([
            'user_id' => $user->id,
            'points' => -$pointsToRedeem,
            'type' => 'redeemed',
            'source' => 'redemption',
            'description' => 'Redeemed ' . $pointsToRedeem . ' points for wallet credit',
        ]);
        
        // In a real application, you would credit the user's wallet or provide other benefits
        
        return redirect()->route('rewards.index')->with('success', 'Successfully redeemed ' . $pointsToRedeem . ' points!');
    }
}