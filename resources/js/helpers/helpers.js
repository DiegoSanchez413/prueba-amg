const formatCurrency = (value) => {
    return "$ " + (value / 100).toFixed(2);
}

const formatDate = (date) => {
    const d = new Date(date)
    const day = d.getDate().toString().padStart(2, '0')
    const monthIndex = (d.getMonth() + 1).toString().padStart(2, '0')
    const year = d.getFullYear()
    return `${day}/${monthIndex}/${year}`
}

export { formatCurrency, formatDate }