'use client'

import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer } from 'recharts'
import { useTranslation } from '../hooks/useTranslation'
import { EnvelopeState } from '../domain/envelope/envelopeTypes'

interface DashboardGraphProps {
    envelopesData: EnvelopeState['envelopesData']
}

export default function DashboardGraph({ envelopesData }: DashboardGraphProps) {
    const { t } = useTranslation()

    if (!envelopesData || envelopesData.envelopes.length === 0) return <div className="text-center">{t('dashboard.noEnvelopes')}</div>

    const data = envelopesData.envelopes.map(envelope => ({
        name: envelope.name,
        current: parseFloat(envelope.currentBudget),
        target: parseFloat(envelope.targetBudget)
    }))

    return (
        <div className="w-full h-[300px] sm:h-[400px] md:h-[500px] neomorphic p-2 sm:p-4">
            <ResponsiveContainer width="100%" height="100%">
                <BarChart
                    data={data}
                    margin={{
                        top: 20,
                        right: 10,
                        left: 0,
                        bottom: 5,
                    }}
                    barSize={20}
                >
                    <CartesianGrid strokeDasharray="3 3" />
                    <XAxis
                        dataKey="name"
                        tick={{ fontSize: 12 }}
                        interval={0}
                        angle={-45}
                        textAnchor="end"
                        height={60}
                    />
                    <YAxis tick={{ fontSize: 12 }} />
                    <Tooltip />
                    <Legend wrapperStyle={{ fontSize: 12 }} />
                    <Bar dataKey="current" fill="#8884d8" name={t('dashboard.currentBudget')} />
                    <Bar dataKey="target" fill="#82ca9d" name={t('dashboard.targetBudget')} />
                </BarChart>
            </ResponsiveContainer>
        </div>
    )
}
