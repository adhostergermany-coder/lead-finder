import { Link } from '@inertiajs/react';

export default function Layout({ children }) {
    return (
        <div className="bg-gray-100 min-h-screen">
            <nav className="bg-indigo-600 text-white shadow-lg">
                <div className="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                    <Link href="/" className="text-xl font-bold">Lead Finder</Link>
                    <div className="space-x-4">
                        <Link href="/" className="hover:text-indigo-200">Leads</Link>
                    </div>
                </div>
            </nav>
            <main className="max-w-7xl mx-auto py-8 px-4">
                {children}
            </main>
        </div>
    );
}
