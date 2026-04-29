@extends('layouts.app')

@section('title', 'Privacy Policy — ' . config('app.name'))

@section('content')
<div class="mx-auto max-w-3xl px-5 py-12">

  <h1 class="text-3xl font-bold text-gray-900 mb-2">Privacy Policy</h1>
  <p class="text-sm text-gray-400 mb-10">Last updated: {{ date('F j, Y') }}</p>

  <div class="prose prose-gray max-w-none space-y-8 text-gray-700 leading-relaxed">

    <section>
      <h2 class="text-xl font-semibold text-gray-800 mb-3">Overview</h2>
      <p>
        {{ config('app.name') }} is a private, invitation-only website created exclusively for members of our class reunion.
        We take your privacy seriously. This policy explains what information we collect, how we use it, and our firm commitment to never sharing it with outside parties.
      </p>
    </section>

    <section>
      <h2 class="text-xl font-semibold text-gray-800 mb-3">Information We Collect</h2>
      <p>When you request access or use this site, we may collect:</p>
      <ul class="list-disc pl-6 mt-2 space-y-1">
        <li><strong>Account information</strong> — your name, email address, and password (stored encrypted).</li>
        <li><strong>Profile details</strong> — graduation year, maiden name, and optional "then &amp; now" updates you choose to share.</li>
        <li><strong>Photos</strong> — images you upload to the site.</li>
        <li><strong>Location data</strong> — your current city and state, only if you opt in to the "Where Are We Now?" map.</li>
        <li><strong>Messages &amp; stories</strong> — memories and other content you submit.</li>
      </ul>
    </section>

    <section>
      <h2 class="text-xl font-semibold text-gray-800 mb-3">How We Use Your Information</h2>
      <p>Your information is used solely to:</p>
      <ul class="list-disc pl-6 mt-2 space-y-1">
        <li>Provide and operate the reunion platform.</li>
        <li>Display your profile and contributions to other verified class members.</li>
        <li>Send you transactional emails (approval confirmation, event updates).</li>
        <li>Improve the site experience.</li>
      </ul>
    </section>

    <section>
      <h2 class="text-xl font-semibold text-gray-800 mb-3">We Do Not Share Your Data</h2>
      <p>
        <strong>Your personal information is never sold, rented, or shared with any third party — full stop.</strong>
        We do not use your data for advertising, and we do not engage with data brokers or marketing platforms.
        The only people who can see your information are other verified members of our class and the site administrators.
      </p>
    </section>

    <section>
      <h2 class="text-xl font-semibold text-gray-800 mb-3">Cookies</h2>
      <p>We use a minimal set of cookies strictly necessary to operate the site:</p>
      <ul class="list-disc pl-6 mt-2 space-y-1">
        <li><strong>Session cookie</strong> — keeps you logged in during your visit.</li>
        <li><strong>CSRF token cookie</strong> — protects your account from cross-site request forgery attacks.</li>
        <li><strong>Analytics cookie</strong> — basic, anonymous page-view analytics to help us understand how the site is used. No personal data is attached.</li>
      </ul>
      <p class="mt-2">We do not use third-party advertising cookies or tracking pixels.</p>
    </section>

    <section>
      <h2 class="text-xl font-semibold text-gray-800 mb-3">Data Security</h2>
      <p>
        All data is transmitted over HTTPS. Passwords are hashed and never stored in plain text. Access to the site and its data is restricted to approved class members only.
      </p>
    </section>

    <section>
      <h2 class="text-xl font-semibold text-gray-800 mb-3">Your Rights</h2>
      <p>
        You may request the deletion of your account and associated data at any time by contacting a site administrator. Once deleted, your information will be permanently removed from our systems.
      </p>
    </section>

    <section>
      <h2 class="text-xl font-semibold text-gray-800 mb-3">Contact</h2>
      <p>
        Questions about this policy? Reach out to a site administrator via the contact information provided in your approval email.
      </p>
    </section>

  </div>

  <div class="mt-10 pt-6 border-t border-gray-200 flex gap-6 text-sm">
    <a href="{{ route('home') }}" class="text-red-700 hover:underline">← Back to home</a>
    <a href="{{ route('terms') }}" class="text-red-700 hover:underline">Terms of Use</a>
  </div>

</div>
@endsection
