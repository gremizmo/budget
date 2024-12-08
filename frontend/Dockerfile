FROM node:18 AS nextjs

WORKDIR /usr/src/app

# Copy package.json and lockfile for Next.js
COPY package.json package-lock.json ./

# Install Node.js dependencies
RUN npm install

# Copy Next.js files
COPY . .

# Expose port for Next.js
EXPOSE 3000

# Run Next.js in development mode
CMD ["npm", "run", "dev"]
