'use client'

import { useAppContext } from '../providers'
import Header from './Header'
import Footer from './Footer'

function LoadingSpinner() {
    return (
        <div className="flex justify-center items-center h-screen">
            <div className="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-primary"></div>
        </div>
    )
}

export function AppContent({ children }: { children: React.ReactNode }) {
    const { state: { loading } } = useAppContext()

    if (loading) {
        return <LoadingSpinner />
    }

    return (
        <>
            <Header />
            <main className="flex-grow container mx-auto px-4 py-8">
                {children}
            </main>
            <Footer />
        </>
    )
}
