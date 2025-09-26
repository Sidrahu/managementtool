<x-dashboard.card 
    title="Projects" 
    :count="$projectsCount" 
    color="blue" 
    icon="M3 7h18M3 12h18M3 17h18" 
/>

<x-dashboard.card 
    title="Boards" 
    :count="$boardsCount" 
    color="green" 
    icon="M9 17v-4h6v4M5 7h14" 
/>

<x-dashboard.card 
    title="Tasks" 
    :count="$tasksCount" 
    color="yellow" 
    icon="M5 13l4 4L19 7" 
/>

<x-dashboard.card 
    title="Quick Actions" 
    count="+" 
    color="purple" 
    icon="M3 3h18v18H3V3z" 
/>
