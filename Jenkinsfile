pipeline {
    agent any
    
    environment {
        NODE_ENV = 'production'
        DOCKER_REGISTRY = 'unmugviolet'
    }
    tools {
        nodejs 'Main NodeJS'
    }
    stages {
        stage('SCM') {
            steps {
                git url: 'git@github.com:UnMugViolet/archi-website.git', branch: 'main'
            }
        }

        stage('SonarQube analysis') {
            steps {
                withSonarQubeEnv('Sonar-Server') {
                    sh "${tool 'SonarScanner'}/bin/sonar-scanner"
                }
            }
        }

        stage('Quality Gate') {
            steps {
                timeout(time: 1, unit: 'HOURS') {
                    waitForQualityGate abortPipeline: true
                }
            }
        }

        stage('Check Docker') {
            steps {
                sh 'docker --version'
                sh 'docker info'
            }
        }

        stage('Build Image') { 
            steps {
                script {
                    try {
                        sh 'ls -la ./Dockerfile || echo "Dockerfile not found"'

                        def appImageName = "${DOCKER_REGISTRY}/archi-website-app:${env.BUILD_NUMBER}"
                        def appLatestImageName = "${DOCKER_REGISTRY}/archi-website-app:latest"
                        def webImageName = "${DOCKER_REGISTRY}/archi-website-web:${env.BUILD_NUMBER}"
                        def webLatestImageName = "${DOCKER_REGISTRY}/archi-website-web:latest"
                        
                        sh """
                            DOCKER_BUILDKIT=0 docker compose build --pull app web

                            docker tag archi-website:prod ${appImageName}
                            docker tag archi-website:prod ${appLatestImageName}
                            docker tag archi-website-web:prod ${webImageName}
                            docker tag archi-website-web:prod ${webLatestImageName}
                        """

                        env.APP_IMAGE_NAME = appImageName
                        env.APP_LATEST_IMAGE_NAME = appLatestImageName
                        env.WEB_IMAGE_NAME = webImageName
                        env.WEB_LATEST_IMAGE_NAME = webLatestImageName

                        echo "Docker images built successfully: ${env.APP_IMAGE_NAME} and ${env.WEB_IMAGE_NAME}"

                    } catch (Exception e) {
                        error "Failed to build Docker image: ${e.getMessage()}"
                    }
                }
            }
        }
        
        stage('Push Image') {
            steps {
                script {
                    docker.withRegistry('https://index.docker.io/v1/', 'dockerhub-credentials') {
                        def appVersionedImage = docker.image("${env.APP_IMAGE_NAME}")
                        def appLatestImage = docker.image("${env.APP_LATEST_IMAGE_NAME}")
                        def webVersionedImage = docker.image("${env.WEB_IMAGE_NAME}")
                        def webLatestImage = docker.image("${env.WEB_LATEST_IMAGE_NAME}")
                        
                        appVersionedImage.push()
                        appLatestImage.push()
                        webVersionedImage.push()
                        webLatestImage.push()
                        
                        echo "Successfully pushed the app and web images to Docker Hub"
                    }
                }
            }
        }
        
        stage('Deploy') {
            steps {
                sshPublisher(
                    publishers: [
                        sshPublisherDesc(
                            configName: 'Infomaniak',
                            transfers: [
                                sshTransfer(
                                    execCommand: '''
                                        cd ~/websites/archi-website &&
                                        echo "🛑 Stopping containers..." &&
                                        docker compose -f docker-compose.yml down &&
                                        echo "📥 Pulling latest images..." &&
                                        docker compose -f docker-compose.yml pull &&
                                        echo "🚀 Starting containers..." &&
                                        docker compose -f docker-compose.yml up -d &&
                                        echo "⏳ Waiting for containers to be ready..." &&
                                        sleep 10 &&
                                        echo "🔧 Running deployment tasks..." &&
                                        docker exec archi-website php artisan migrate --force &&
                                        docker exec archi-website php artisan optimize &&
                                        echo "🧹 Cleaning up..." &&
                                        docker system prune -f &&
                                        docker image prune -f &&
                                        echo "✅ Deployment completed successfully!"
                                    '''
                                )
                            ]
                        )
                    ]
                )
            }
        }
    }
}
