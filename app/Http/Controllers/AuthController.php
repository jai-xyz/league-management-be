<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\Email;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\UserModel;

class AuthController extends Controller
{

    public function login(Request $request)
    {

        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Attempt to verify the credentials
            $user = UserModel::where('email', $request->email)->first();

            // Check if the user exists
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Check if the password is correct
            if (!Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            // if user exists add JWT token with custom claims that expires in 10 years
            $token = JWTAuth::customClaims(['exp' => now()->addYears(10)->timestamp])->fromUser($user);

            DB::commit();

            // Return the token and user information
            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user,
            ], 200);
        } catch (JWTException $e) {
            DB::rollBack();
            // Return an error response if token creation fails
            return response()->json([
                'status' => false,
                'message' => 'Could not create token',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        // Invalidate the token
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'message' => 'Successfully logged out',
                'status' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Could not invalidate token.' . $e->getMessage(),
                'status' => false
            ], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        // Validate the request
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        // Check if validation fails
        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors(), 401]);
        };

        try {
            DB::beginTransaction();

            // Find the user by email
            $user = UserModel::where('email', $request->email)->first();

            // Check if the user exists
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Generate a new password, a random string of 12 characters
            //  ? CAN ADD AN EXPIRATION TIME FOR THE PASSWORD
            $newPassword = Str::random(12, 'alphanum');

            // Update the user's password
            $user->password = bcrypt($newPassword);
            $user->save();

            // TODO: Commented out email sending for now, need to configure .env file and email account
            // Prepare the email data
            // $emailData = [
            //     'template' => 'forgotPassword',
            //     'subject' => 'Forgot Password Notification',
            //     'email' => $user->email,
            //     'name' => $user->name,
            //     'newPassword' => $newPassword,
            // ];

            // $sentEmail = $this->sendEmail($emailData);

            // if ($sentEmail['status'] === 500) {
            //     return response()->json($sentEmail, 500);
            // }

            DB::commit();
            return response()->json([
                'message' => 'Password reset successfully',
                'new_password' => $newPassword,
                'status' => true,
                'user' => $user->id,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error occurred while processing your request, ' . $e->getMessage()
            ], 500);
        }
    }

    public function sendEmail($data)
    {
        try {
            Mail::to($data['email'])->send(new Email($data));
            // Email sent successfully
            return [
                "data" => $data,
                'message' => 'Email sent successfully',
                'status' => 200
            ];
        } catch (\Exception $e) {
            // Error occurred while sending the email
            return [
                "data" => $data,
                'message' => 'Email could not be sent',
                'status' => 500,
                'error' =>  $e->getMessage()
            ];
        }
    }

    public function changePassword(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8|same:new_password',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors(), 401]);
        };

        try {
            DB::beginTransaction();

            // Find the user by email
            $user = UserModel::where('email', $request->email)->first();

            // Check if the user exists
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Check if the old password is correct
            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            // Update the user's password
            $user->password = bcrypt($request->new_password);
            $user->save();

            DB::commit();
            return response()->json([
                'message' => 'Password changed successfully',
                'status' => true,
                'user' => $user->id,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error occurred while processing your request, ' . $e->getMessage()
            ], 500);
        }
    }

    public function register(Request $request)
    {
        // Validate the request
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8|same:password',
        ]);

        // Check if validation fails
        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors(), 401]);
        };

        try {
            DB::beginTransaction();

            // Create a new user
            $user = UserModel::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            DB::commit();
            return response()->json([
                'message' => 'User registered successfully',
                'status' => true,
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error occurred while processing your request, ' . $e->getMessage()
            ], 500);
        }
    }
}
