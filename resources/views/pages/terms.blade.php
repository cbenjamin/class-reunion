@extends('layouts.app')

@section('title', 'Terms of Use — ' . config('app.name'))

@section('content')
<div class="mx-auto max-w-3xl px-5 py-12">

  <h1 class="text-3xl font-bold text-gray-900 mb-2">Terms of Use</h1>
  <p class="text-sm text-gray-400 mb-10">Last updated: {{ date('F j, Y') }}</p>

  <div class="prose prose-gray max-w-none space-y-8 text-gray-700 leading-relaxed">

    <section>
      <h2 class="text-xl font-semibold text-gray-800 mb-3">Agreement to Terms</h2>
      <p>
        By accessing or using {{ config('app.name') }} (the "Site"), you agree to be bound by these Terms of Use.
        If you do not agree with any part of these terms, please do not use the Site.
      </p>
    </section>

    <section>
      <h2 class="text-xl font-semibold text-gray-800 mb-3">Eligibility &amp; Access</h2>
      <p>
        This Site is a private platform for verified members of our class reunion.
        Access is granted by invitation only. You must not share your login credentials or provide access to anyone who has not been approved by a site administrator.
      </p>
    </section>

    <section>
      <h2 class="text-xl font-semibold text-gray-800 mb-3">Acceptable Use</h2>
      <p>You agree to use the Site in a respectful, lawful manner. You must not:</p>
      <ul class="list-disc pl-6 mt-2 space-y-1">
        <li>Post content that is unlawful, defamatory, harassing, or otherwise harmful.</li>
        <li>Upload photos or material in which you do not have the right to share.</li>
        <li>Impersonate another person or provide false information.</li>
        <li>Use the Site for any commercial purpose.</li>
        <li>Attempt to gain unauthorized access to any part of the Site or its systems.</li>
        <li>Scrape, harvest, or systematically download content from the Site.</li>
      </ul>
    </section>

    <section>
      <h2 class="text-xl font-semibold text-gray-800 mb-3">Content You Submit</h2>
      <p>
        By submitting photos, memories, stories, or any other content to the Site, you confirm that you have the right to share that content and grant us a limited license to display it within the Site for other approved class members.
        Content is not licensed for public distribution outside of this private platform.
      </p>
      <p class="mt-2">
        Site administrators reserve the right to remove any content that violates these terms or is otherwise deemed inappropriate, without prior notice.
      </p>
    </section>

    <section>
      <h2 class="text-xl font-semibold text-gray-800 mb-3">Privacy</h2>
      <p>
        Your use of the Site is also governed by our <a href="{{ route('privacy') }}" class="text-red-700 hover:underline">Privacy Policy</a>, which is incorporated into these Terms by reference.
      </p>
    </section>

    <section>
      <h2 class="text-xl font-semibold text-gray-800 mb-3">Disclaimer of Warranties</h2>
      <p>
        The Site is provided "as is" without warranties of any kind. We do not guarantee that the Site will be available at all times or that it will be free from errors.
        This is a volunteer-operated platform maintained on a best-effort basis.
      </p>
    </section>

    <section>
      <h2 class="text-xl font-semibold text-gray-800 mb-3">Limitation of Liability</h2>
      <p>
        To the fullest extent permitted by law, {{ config('app.name') }} and its administrators shall not be liable for any indirect, incidental, or consequential damages arising from your use of the Site.
      </p>
    </section>

    <section>
      <h2 class="text-xl font-semibold text-gray-800 mb-3">Changes to These Terms</h2>
      <p>
        We may update these Terms from time to time. Continued use of the Site after any changes constitutes acceptance of the revised Terms. The "last updated" date at the top of this page will reflect any changes.
      </p>
    </section>

    <section>
      <h2 class="text-xl font-semibold text-gray-800 mb-3">Termination</h2>
      <p>
        Administrators may suspend or revoke your access at any time for any violation of these Terms.
        You may request account deletion by contacting a site administrator.
      </p>
    </section>

  </div>

  <div class="mt-10 pt-6 border-t border-gray-200 flex gap-6 text-sm">
    <a href="{{ route('home') }}" class="text-red-700 hover:underline">← Back to home</a>
    <a href="{{ route('privacy') }}" class="text-red-700 hover:underline">Privacy Policy</a>
  </div>

</div>
@endsection
